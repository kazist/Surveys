<?php
/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of SurveysController
 *
 * @author sbc
 */

namespace Surveys\Surveys\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Kazist\KazistFactory;

class SurveysController extends BaseController {

    /**
     * Save functions
     *
     * @return  void
     *
     * @since   1.0
     * @throws  \RuntimeException
     */
    public function saveAction() {
        $redirect = '';

        $factory = new KazistFactory();

        $session = $factory->getSession();

        $form = $this->request->request->get('form');
        $session->set('session_form', $form);

        $survey_id = $this->model->saveSurvey($form);

        if ($survey_id) {
            $msg = $form['title'] . 'Survey Added';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('surveys.surveys.detail&id=' . $survey_id);
        } else {
            $msg = 'Survey not added;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('surveys.surveys');
        }

        $this->redirect($redirect);
    }

    public function ajaxloadsurveyformAction() {

        $factory = new KazistFactory();

        $survey_id = $this->request->get('survey_id');

        echo $this->model->loadSurveyForm($survey_id);
        exit;
    }

    public function ajaxsavesurveyAction() {

        $data_obj = new \stdClass();

        $form = $this->request->request->get('form');

        $survey_id = $this->model->saveSurvey($form);

        $data_obj->survey_id = $survey_id;
        $data_obj->successful = true;

        echo json_encode($data_obj);
        exit;
    }
    
  
    public function ajaxloadresultAction() {

        $survey_id = $this->request->request->get('survey_id');
        $show_result = $this->request->request->get('show_result');

        echo $this->model->loadSurveyTakingForm($survey_id, $show_result);
        exit;
    }


    public function ajaxsaveanswersAction() {

        $data_obj = new \stdClass();
        $factory = new KazistFactory();

        $survey = $this->request->get('survey');

        $result = $this->model->saveSurveyAnswers($survey);

        $data_obj->survey_id = $survey['id'];
        $data_obj->successful = true;

        echo json_encode($result);
        exit;
    }

}
