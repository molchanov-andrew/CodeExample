<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/10/18
 * Time: 9:53 AM
 */

namespace app\modules\rest\controllers;


use app\modules\rest\models\PActiveController;

class LotteryTimerController extends PActiveController
{
    public $modelClass = 'app\models\records\LotteryTimer';
    public $searchModelClass = 'app\models\search\LotteryTimerSearch';
}