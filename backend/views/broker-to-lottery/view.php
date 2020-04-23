<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerToLottery */
/* @var $parentModel  common\models\records\Lottery */


$this->title = $model->getLottery()->one()->name;
$this->params['breadcrumbs'][] = ['label' => 'Broker To Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="broker-to-lottery-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
//            'id',
            [
                'label' => 'Broker',
                'value' => $model->getBroker()->one()->name
            ],
            [
                'label' => 'Lottery',
                'value' => $model->getLottery()->one()->name
            ],
            'syndicat',
            'price',
            'url:url',
        ],
    ]) ?>

</div>
