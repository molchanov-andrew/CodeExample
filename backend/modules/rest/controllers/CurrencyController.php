<?php

namespace app\modules\rest\controllers;

use app\modules\rest\models\PActiveController;

class CurrencyController extends PActiveController
{
    public $modelClass = 'app\models\records\Currency';
    public $searchModelClass = 'app\models\search\CurrencySearch';
}