<?php

namespace app\modules\rest\controllers;

use app\modules\rest\models\PActiveController;

class SettingController extends PActiveController
{
    public $modelClass = 'app\models\records\Setting';
    public $searchModelClass = 'app\models\search\SettingSearch';
}