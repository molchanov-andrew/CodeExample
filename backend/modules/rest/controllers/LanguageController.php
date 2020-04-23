<?php

namespace app\modules\rest\controllers;

use app\modules\rest\models\PActiveController;

class LanguageController extends PActiveController
{
    public $modelClass = 'app\models\records\Language';
    public $searchModelClass = 'app\models\search\LanguageSearch';
}