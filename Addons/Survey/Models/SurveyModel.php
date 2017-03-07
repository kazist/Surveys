<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Surveys\Addons\Survey\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class SurveyModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getSurvey() {

        $query = $this->getQuery();

        $records = $query->loadObject();

        return $records;
    }

    public function getQuery() {
        $factory = new KazistFactory;
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('ss.*');
        $query->from('#__surveys_surveys', 'ss');
        $query->where('ss.published=1');
        $query->where('ss.featured=1');
        $query->orderBy('ss.id', 'DESC');

        return $query;
    }

}
