<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerPhone */

$this->title = 'Update Broker Phone: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Broker Phones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broker-phone-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'countries' => $countries,
    ]) ?>

</div>
