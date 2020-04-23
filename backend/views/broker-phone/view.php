<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerPhone */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Broker Phones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-phone-view">

    <h1><?= Html::encode($model->broker->name) . ' : ' . $model->phone ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'brokerId',
            [
                'label' => 'Broker',
                'value' => Html::encode($model->broker->name)
            ],
            'countryId',
            [
'label' => 'Country',
                'value' => Html::encode($model->country->name)
            ],
            'phone',
        ],
    ]) ?>

</div>
