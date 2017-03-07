<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Surveys\Addons\Survey\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Surveys\Addons\Survey\Models\SurveyModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class SurveyController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new SurveyModel;

        $survey = $model->getSurvey();

        $data_arr['survey'] = $survey;

        $this->html = $this->render('Surveys:Addons:Survey:views:survey.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
