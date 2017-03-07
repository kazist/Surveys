<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of QuestionsController
 *
 * @author sbc
 */

namespace Surveys\Questions\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Surveys\Questions\Code\Models\QuestionModel;
use Kazist\KazistFactory;

class QuestionsController extends BaseController {

    public function saveAction() {
        $redirect = '';

        $factory = new KazistFactory();

        $form = $this->request->get('form');

        $question_id = $this->model->saveQuestion($form);

        if ($question_id) {
            $msg = $form['title'] . 'Question Added';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('surveys.questions.edit', array('id', $question_id));
        } else {
            $msg = 'Question not added;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('surveys.questions');
        }

        $this->redirect($redirect);
    }

    public function ajaxloadquestionform() {

       
        $type = $this->request->get('type', 'new');

        echo $this->model->loadQuestionForm($type);
        exit;
    }

    public function ajaxaddquestion() {

        $data_obj = new \stdClass();

        $form = $this->request->get('form');

        $question_id = $this->model->saveQuestion($form);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function ajaxaddsurveyquestion() {

        $data_obj = new \stdClass();

        $form = $this->request->get('form');

        $question_id = $this->model->addQuestionSurvey($form['survey_id'], $form['question_id']);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function ajaxdeletesurveyquestion() {

        $data_obj = new \stdClass();

        $form = $this->request->get('form');

        $question_id = $this->model->deleteQuestionSurvey($form['id'], $form['survey_id'], $form['question_id']);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

}
