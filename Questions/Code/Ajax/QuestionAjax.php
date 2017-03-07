<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Surveys\Question\Ajax;

defined('KAZIST') or exit('Not Kazist Framework');

use Surveys\Question\Models\QuestionModel;
use Kazist\KazistFactory;

/**
 * Dashboard Controller class for the Application
 *
 * @since  1.0
 */
class QuestionAjax {

    /**
     * Save functions
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function ajaxloadquestionform() {

        $factory = new KazistFactory();
        $input = $factory->getInput();
        $type = $this->request->request->get('type', 'new');

        $questionModel = new QuestionModel();
        echo $questionModel->loadQuestionForm($type);
        exit;
    }

    public function ajaxaddquestion() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $input = $factory->getInput();
        $session = $factory->getSession();

        $form = $this->request->request->get('form', null, 'raw');

        $questionModel = new QuestionModel();
        $question_id = $questionModel->saveQuestion($form);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function ajaxaddsurveyquestion() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $input = $factory->getInput();

        $form = $this->request->request->get('form', null, 'raw');

        $questionModel = new QuestionModel();
        $question_id = $questionModel->addQuestionSurvey($form['survey_id'], $form['question_id']);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function ajaxdeletesurveyquestion() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $input = $factory->getInput();

        $form = $this->request->request->get('form', null, 'raw');

        $questionModel = new QuestionModel();
        $question_id = $questionModel->deleteQuestionSurvey($form['id'], $form['survey_id'], $form['question_id']);

        $data_obj->question_id = $question_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

}
