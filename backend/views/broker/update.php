<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\Broker */

$this->title = 'Update Broker: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Brokers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'statuses' => $statuses,
        'bonuses' => $bonuses,
        'languages' => $languages,
        'paymentMethods' => $paymentMethods,
    ]) ?>

</div>
