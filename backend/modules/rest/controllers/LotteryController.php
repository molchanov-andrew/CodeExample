<?php

namespace app\modules\rest\controllers;

use app\modules\rest\models\PActiveController;

class LotteryController extends PActiveController
{
    public $modelClass = 'app\models\records\Lottery';
    public $searchModelClass = 'app\models\search\LotterySearch';
}