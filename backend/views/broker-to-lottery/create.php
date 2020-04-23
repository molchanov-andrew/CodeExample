<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerToLottery */

$this->title = "Create {$parentModel->name} relation";
$this->params['breadcrumbs'][] = ['label' => 'Broker To Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-to-lottery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'brokers' => $brokers,
        'lotteries' => $lotteries,
    ]) ?>

</div>
