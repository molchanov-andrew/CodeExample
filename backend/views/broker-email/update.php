<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerEmail */

$this->title = 'Update Broker Email: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Broker Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broker-email-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
