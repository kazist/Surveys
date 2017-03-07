<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Surveys\Addons\Rating\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Surveys\Addons\Rating\Models\RatingModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class RatingController extends AddonController {

    function indexAction() {

        $model = new RatingModel;

        $rating = $model->getRating();

        $data_arr['rating'] = $rating;

        $this->html = $this->render('Surveys:Addons:Rating:views:rating.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
