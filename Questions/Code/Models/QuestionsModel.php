<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Surveys\Questions\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of QuestionModel
 *
 * @author sbc
 */
class QuestionsModel extends BaseModel {

//put your code here


    public function getQuestions() {

        $tmp_array = array();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $query = new Query();
        $query->select('sq.*');
        $query->from('#__surveys_questions', 'sq');
        if (WEB_IS_ADMIN) {
            $query->where('sq.created_by=:user_id');
            $query->setParameter('user_id', $user->id);
        }
        $query->orderBy('sq.id', 'DESC');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->question);
            }
        }

        return $tmp_array;
    }

    public function getQuestionParameters($id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $query = new Query();
        $query->select('sqp.*');
        $query->from('#__surveys_questions_parameters', 'sqp');
        if ($id) {
            $query->where('sqp.question_id=:question_id');
            $query->setParameter('question_id', $id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('sqp.id ', 'DESC');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $records[$key]->answer = $this->getQuestionParameterAnswer($id, $record->id);
            }
        }

        return $records;
    }

    public function getQuestionResult($survey_id, $question_id, $question_type) {

        $tmp_array = array();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $query = new Query();

        if ($question_type == 'text') {
            $query->select('sqr.result as label, COUNT(sqr.result) AS result');
        } else {
            $query->select('sqp.label as label, COUNT(sqr.result) AS result');
            $query->leftJoin('sqr', '#__surveys_questions_parameters', 'sqp', 'sqr.result = sqp.id');
        }

        $query->from('#__surveys_surveys_results', 'sqr');
        $query->leftJoin('sqr', '#__surveys_surveys_users', 'ssu', 'ssu.id = sqr.survey_user_id');


        if ($question_id && $survey_id) {
            $query->where('ssu.survey_id=:survey_id');
            $query->andWhere('sqr.question_id=:question_id');
            $query->setParameter('survey_id', $survey_id);
            $query->setParameter('question_id', $question_id);
        } else {
            $query->where('1=-1');
        }

        $query->orderBy('sqr.result');
        $query->groupBy('sqr.result');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getQuestionParameterAnswer($question_id, $parameter_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $query = new Query();
        $query->select('sqa.*');
        $query->from('#__surveys_questions_answers', 'sqa');
        if ((int) $parameter_id && (int) $question_id) {
            $query->where('sqa.question_id=:question_id');
            $query->andWhere('sqa.parameter_id=:parameter_id');
            $query->setParameter('question_id', $question_id);
            $query->setParameter('parameter_id', $parameter_id);
        } else {
            $query->where('1=-1');
        }
        $query->orderBy('sqa.id ', 'DESC');

        $record = $query->loadObject();

        if (is_object($record)) {
            return $record->value;
        }

        return false;
    }

    public function getQuestionTypes() {
        $tmp_array = array();

        $tmp_array[] = array('value' => 'radio', 'text' => 'Radio');
        $tmp_array[] = array('value' => 'radioimage', 'text' => 'Radio with Image');
        $tmp_array[] = array('value' => 'checkbox', 'text' => 'Checkbox');
        $tmp_array[] = array('value' => 'checkboximage', 'text' => 'Checkbox with Image');
        $tmp_array[] = array('value' => 'text', 'text' => 'Text');
        $tmp_array[] = array('value' => 'dropdown', 'text' => 'Dropdown');

        return $tmp_array;
    }

    public function getQuestionCharts() {
        $tmp_array = array();

        $tmp_array[] = array('value' => 'pie', 'text' => 'Pie');
        $tmp_array[] = array('value' => 'donut', 'text' => 'Donut');
        $tmp_array[] = array('value' => 'bar', 'text' => 'Bar');

        return $tmp_array;
    }

    public function saveQuestion($form) {
        return $this->save($form);
    }

    public function save($form) {

        $query = new Query();
        $factory = new KazistFactory();

        $data_obj = new \stdClass();
        $data_obj->id = $form['id'];
        $data_obj->type = $form['type'];
        $data_obj->chart = $form['chart'];
        $data_obj->question = $form['question'];

        $question_id = $factory->saveRecord('#__surveys_questions', $data_obj);

        if ($form['survey_id']) {
            $this->addQuestionSurvey($form['survey_id'], $question_id);
        }

        $media_ids = $factory->uploadMedia('form.quiz_params.image', 'surveys.question', $question_id);

        $new_parameter_arr = $this->saveQuestionParameters($form['quiz_params']['new'], $question_id, $media_ids, 'new');
        $exist_parameter_arr = $this->saveQuestionParameters($form['quiz_params']['exist'], $question_id, $media_ids, 'exist');

        $parameter_arr = array_merge($new_parameter_arr, $exist_parameter_arr);


        $query->delete('#__surveys_questions_parameters');
        $query->andWhere('question_id=:question_id');
        $query->setParameter('question_id', $question_id);
        if (!empty($parameter_arr)) {
            $query->andWhere('id NOT IN(' . implode(', ', $parameter_arr) . ')');
        }
        $query->execute();

        return $question_id;
    }

    public function addQuestionSurvey($survey_id, $question_id) {

        $factory = new KazistFactory();

        $params_obj = new \stdClass();
        $params_obj->question_id = (int) $question_id;
        $params_obj->survey_id = (int) $survey_id;
        $params_obj->id = $this->getQuestionSurveyId($survey_id, $question_id);

        $factory->saveRecord('#__surveys_surveys_questions', $params_obj);
    }

    public function deleteQuestionSurvey($id, $survey_id, $question_id) {

        $query = new Query();
        $query->delete('#__surveys_surveys_questions');

        if ((int) $id) {
            $query->where('id =' . (int) $id);
            $query->setParameter('$id', $id);
        } elseif ((int) $survey_id && (int) $question_id) {
            $query->where('survey_id=:survey_id');
            $query->andWhere('question_id=:question_id');
            $query->setParameter('survey_id', $survey_id);
            $query->setParameter('question_id', $question_id);
        } else {
            $query->where('1=-1');
        }


        if ((int) $survey_id && (int) $question_id) {
            $query->execute();
        }
    }

    public function getQuestionSurveyId($survey_id, $question_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('ssq.*');
        $query->from('#__surveys_surveys_questions', 'ssq');
        if ((int) $survey_id && (int) $question_id) {
            $query->where('ssq.survey_id=:survey_id');
            $query->andWhere('ssq.question_id=:question_id');
            $query->setParameter('survey_id', $survey_id);
            $query->setParameter('question_id', $question_id);
        } else {
            $query->where('1=-1');
        }
        $record = $query->loadObject();

        if (is_object($record)) {
            return $record->id;
        } else {
            return false;
        }
    }

    public function saveQuestionParameters($parameters, $question_id, $media_ids, $type = 'new') {

        $parameter_id_arr = array();
        $factory = new KazistFactory();

        if (!empty($parameters['label'])) {
            foreach ($parameters['label'] as $key => $option) {

                $data_obj = new \stdClass();
                $data_obj->question_id = $question_id;
                $data_obj->label = $option;

                if ($media_ids[$key] != '') {
                    $data_obj->image = $media_ids[$key];
                }

                if ($type == 'exist') {
                    $data_obj->id = $key;
                }

                $parameter_id = $factory->saveRecord('#__surveys_questions_parameters', $data_obj);
                $value = $parameters['answer'][$key];

                if ($value) {
                    $this->saveQuestionValue($question_id, $parameter_id, $value);
                }

                $parameter_id_arr[] = $parameter_id;
            }
        }

        return $parameter_id_arr;
    }

    public function saveQuestionValue($question_id, $parameter_id, $value) {
        $factory = new KazistFactory();
        $data_obj = new \stdClass();

        $data_obj->question_id = (int) $question_id;
        $data_obj->parameter_id = (int) $parameter_id;
        $data_obj->value = $value;

        $factory->saveRecord('#__surveys_questions_answers', $data_obj);
    }

    public function loadQuestionForm($type) {

        $paths = array();

        $object_arr = array();
        $factory = new KazistFactory();

        $template = 'Surveys:generalviews:question:question.' . $type . '.form.twig';
        $paths[] = realpath(JPATH_ROOT . '/applications/Surveys/generalviews/question');

        $html = $factory->renderData($template, $object_arr, $paths);

        return $html;
    }

}
