<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerEmail */

$this->title = 'Create Broker Email';
$this->params['breadcrumbs'][] = ['label' => 'Broker Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-email-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
