<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerStatus */

$this->title = 'Create Broker Status';
$this->params['breadcrumbs'][] = ['label' => 'Broker Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
    ]) ?>

</div>
