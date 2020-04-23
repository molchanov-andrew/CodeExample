<?php
/* @var $lotteryLogo \common\models\records\Image */

use Yii;
use yii\helpers\Html;

echo Html::img(Yii::$app->params['storagePath'] . $lotteryLogo->getFilePath(), ['id' => 'lotteryLogo', 'alt' => 'lottery_logo']);
