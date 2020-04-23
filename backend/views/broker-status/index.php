<?php

use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\image\RelativeImageColumn;
use backend\models\grid\CustomCheckboxColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BrokerStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Broker Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-status-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Broker Status', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change multiple',['change-multiple'],[
            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
            'data-toggle'=>'modal',
            'data-target'=>'#modalGeneral',
            'data-pjax' => '0',
        ]); ?>
        <?= Html::a('Delete', ['delete-multiple'], ['class' => 'btn btn-danger ajax-solo-rows',
            'data-solo-confirm' => Yii::t('yii', 'Are you sure you want to delete this items?'),]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => CustomCheckboxColumn::class, 'limitFilter' => true],
            ['class' => SerialColumn::class],

            'name',
            'isPositive',
            [
                'class' => RelativeImageColumn::class,
                'header' => 'Main page image',
                'imageField' => 'mainPageImage',
            ],
            [
                'class' => RelativeImageColumn::class,
                'header' => 'Brokers list image',
                'imageField' => 'listImage',
            ],
            [
                'class' => RelativeImageColumn::class,
                'header' => 'Broker page image',
                'imageField' => 'brokerPageImage',
            ],

            ['class' => ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
