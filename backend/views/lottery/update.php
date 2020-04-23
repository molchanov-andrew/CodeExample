<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\Lottery */
/* @var $countries array common\models\records\Country */

$this->title = 'Update Lottery: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lottery-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'countries' => $countries,
    ]) ?>

</div>
