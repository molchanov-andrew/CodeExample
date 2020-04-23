<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/10/18
 * Time: 9:52 AM
 */

namespace app\modules\rest\controllers;


use app\modules\rest\models\PActiveController;

class LotteryResultController extends PActiveController
{
    public $modelClass = 'app\models\records\LotteryResult';
    public $searchModelClass = 'app\models\search\LotteryResultSearch';
}
