<?php

/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Surveys\Survey\Ajax;

defined('KAZIST') or exit('Not Kazist Framework');

use Surveys\Surveys\Models\SurveyModel;
use Kazist\KazistFactory;

/**
 * Dashboard Controller class for the Application
 *
 * @since  1.0
 */
class SurveyAjax {

    /**
     * Save functions
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function ajaxloadsurveyform() {

        $factory = new KazistFactory();
        $input = $factory->getInput();
        $survey_id = $this->request->request->get('survey_id');
        $show_result = $this->request->request->get('show_result');
        $hide_on_new = $this->request->request->get('hide_on_new');

        $surveyModel = new SurveyModel();
        echo $surveyModel->loadSurveyForm($survey_id, $show_result, $hide_on_new);
        exit;
    }

    public function ajaxloadresult() {

        $factory = new KazistFactory();
        $input = $factory->getInput();
        $survey_id = $this->request->request->get('survey_id');
        $show_result = $this->request->request->get('show_result');

        $surveyModel = new SurveyModel();
        echo $surveyModel->loadSurveyTakingForm($survey_id, $show_result);
        exit;
    }

    public function ajaxsavesurvey() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $input = $factory->getInput();
        $session = $factory->getSession();

        $form = $this->request->request->get('form', null, 'raw');

        $surveyModel = new SurveyModel();
        $survey_id = $surveyModel->saveSurvey($form);

        $data_obj->survey_id = $survey_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }

    public function ajaxsaveanswers() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();
        $input = $factory->getInput();
        $session = $factory->getSession();

        $survey = $this->request->request->get('survey', null, 'raw');

        $surveyModel = new SurveyModel();
        $result = $surveyModel->saveSurveyAnswers($survey);

        //$data_obj->survey_id = $survey_id;
        //$data_obj->successful = true;

        echo json_encode($result);
        exit;
    }

}
