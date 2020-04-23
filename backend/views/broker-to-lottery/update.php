<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerToLottery */

$this->title = "Update {$parentModel->name}: {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Broker To Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broker-to-lottery-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'brokers' => $brokers,
        'lotteries' => $lotteries,
    ]) ?>

</div>
