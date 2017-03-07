<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Surveys\Surveys\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\ BaseModel;
use Kazist\KazistFactory;
use Surveys\Questions\Code\Models\QuestionsModel;
use Kazist\Service\Database\Query;

/**
 * Description of QuestionModel
 *
 * @author sbc
 */
class SurveysModel extends BaseModel {

    public function saveSurvey($form) {

        $factory = new KazistFactory();

        $data_obj = new \stdClass();
        $data_obj->id = $form['id'];
        $data_obj->title = $form['title'];
        $data_obj->description = $form['description'];

        $survey_id = $factory->saveRecord('#__surveys_surveys', $data_obj);

        return $survey_id;
    }

    public function saveSurveyAnswers($survey) {
        $response_obj = new \stdClass();
        $factory = new KazistFactory();
        $user = $factory->getUser();
        $survey_id = $survey['id'];

        $survey_obj = $this->getSurvey($survey_id);
        $total_takes = $this->getUserTotalTakes($survey_id, $user->id, $user->guest_id);

        if (!$survey_obj->no_of_takes || $total_takes < $survey_obj->no_of_takes) {

            $questions = $survey['question'];

            $survey_user_id = $this->saveSurveyUser($survey_id, $user->id, $user->guest_id);
            $this->saveSurveyQuestionsAnswers($questions, $user, $survey_user_id);

            $response_obj->successful = true;
        } else {

            $response_obj->message = 'Your have reached maximum takes.';
            $response_obj->successful = false;
        }

        return $response_obj;
    }

    public function saveSurveyQuestionsAnswers($questions, $user, $survey_user_id) {
        if (!empty($questions)) {
            foreach ($questions as $question_id => $question) {

                $answers = (isset($question['answer'])) ? $question['answer'] : '';

                if (!empty($answers)) {
                    foreach ($answers as $answer) {
                        $this->saveSurveyResult($question_id, $survey_user_id, $user->id, $user->guest_id, $answer);
                    }
                }
            }
        }
    }

    public function saveSurveyResult($question_id, $survey_user_id, $user_id, $guest_id, $result) {
        $factory = new KazistFactory();

        $data_obj = new \stdClass();
        $data_obj->question_id = $question_id;
        $data_obj->survey_user_id = $survey_user_id;
        $data_obj->user_id = $user_id;
        $data_obj->guest_id = $guest_id;
        $data_obj->result = $result;

        return $factory->saveRecord('#__surveys_surveys_results', $data_obj);
    }

    public function saveSurveyUser($survey_id, $user_id, $guest_id) {
        $factory = new KazistFactory();

        $data_obj = new \stdClass();
        $data_obj->survey_id = $survey_id;
        $data_obj->user_id = $user_id;
        $data_obj->guest_id = $guest_id;

        return $factory->saveRecord('#__surveys_surveys_users', $data_obj);
    }

    public function getUserTotalTakes($survey_id, $user_id, $guest_id) {

        $query = new Query();

        $query->select('COUNT(*) as total');
        $query->from('#__surveys_surveys_users', 'ssu');
        $query->where('1=1');
        if ($survey_id && ($user_id || $guest_id )) {
            $query->andWhere('ssu.survey_id=:survey_id');
            $query->andWhere('(ssu.user_id= :user_id OR ssu.guest_id=:guest_id)');
            $query->setParameter('survey_id', $survey_id);
            $query->setParameter('user_id', $user_id);
            $query->setParameter('guest_id', $guest_id);
        } else {
            $query->where('1=-1');
        }

        $record = $query->loadObject();

        return $record->total;
    }

    public function getSurvey($survey_id) {

        $query = new Query();
        $query->select('ss.*');
        $query->from('#__surveys_surveys', 'ss');
        if ($survey_id) {
            $query->andWhere('id=:survey_id');
            $query->setParameter('survey_id', $survey_id);
        } else {
            $query->andWhere('1=-1');
        }

        $records = $query->loadObject();

        return $records;
    }

    public function getSurveyNQuestions($survey_id) {

        $factory = new KazistFactory();
        $user = $factory->getUser();
        $questionModel = new QuestionsModel();

        $survey = $this->getSurvey($survey_id);
        $survey->questions = $this->getSurveyQuestions($survey_id);
        $survey->total_takes = $this->getUserTotalTakes($survey_id, $user->id, $user->guest_id);

        if (!empty($survey->questions)) {
            foreach ($survey->questions as $key => $question) {
                $survey->questions[$key]->parameters = $questionModel->getQuestionParameters($question->id);
                $survey->questions[$key]->result = $questionModel->getQuestionResult($survey_id, $question->id, $question->type);
            }
        }

        return $survey;
    }

    public function getSurveyQuestions($survey_id) {

        $query = new Query();
        $query->select('sq.*, ssq.id as survey_question_id');
        $query->from('#__surveys_surveys_questions', 'ssq');
        $query->leftJoin('ssq', '#__surveys_questions', 'sq', 'sq.id = ssq.question_id');
        $query->where('ssq.survey_id=:survey_id');
        $query->setParameter('survey_id', $survey_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getSurveyNoOfTakes() {

        $tmp_array = array();

        for ($x = 0; $x < 11; $x++) {
            if (!$x) {
                $tmp_array[] = array('value' => $x, 'text' => 'Unlimited Takes');
            } else {
                $tmp_array[] = array('value' => $x, 'text' => $x . ' Takes');
            }
        }

        return $tmp_array;
    }

    public function loadSurveyForm($survey_id, $show_result = false, $hide_on_new = false) {

        $paths = array();

        $factory = new KazistFactory();

        $object_arr = array();
        $object_arr['item'] = $this->getSurvey($survey_id);
        $object_arr['survey_id'] = $survey_id;
        $object_arr['show_result'] = $show_result;
        $object_arr['hide_on_new'] = $hide_on_new;

        $template = 'Surveys:generalviews:survey:survey.new.form.twig';
        $paths[] = realpath(JPATH_ROOT . '/applications/Surveys/generalviews/survey');

        $html = $factory->renderData($template, $object_arr, $paths);

        return $html;
    }

    public function loadSurveyTakingForm($survey_id, $show_result = false) {

        $paths = array();

        $factory = new KazistFactory();

        $object_arr = array();
        $object_arr['item'] = $this->getSurvey($survey_id);
        $object_arr['survey_id'] = $survey_id;
        $object_arr['show_result'] = $show_result;

        $template = 'Surveys:generalviews:survey:user.survey.twig';
        $paths[] = realpath(JPATH_ROOT . '/applications/Surveys/generalviews/survey');

        $html = $factory->renderData($template, $object_arr, $paths);
    
        return $html;
    }

}
