<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerPhone */

$this->title = 'Create Broker Phone';
$this->params['breadcrumbs'][] = ['label' => 'Broker Phones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-phone-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'countries' => $countries,
    ]) ?>

</div>
