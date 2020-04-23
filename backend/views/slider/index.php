<?php

use backend\models\image\RelativeImageColumn;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\CustomCheckboxColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SliderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sliders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Slider', ['create'], ['class' => 'btn btn-success']) ?>
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

            [
                'attribute' => 'languageId',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'languageId', array_column($languages,'name','id'), ['class'=>'form-control','prompt' => 'Select languageId']),
                'value' => function(\common\models\records\Slider $model){
                    return Html::a($model->language->name,['/language/view','id' => $model->language->id]);
                }
            ],
            [
                'class' => RelativeImageColumn::class,
                'imageField' => 'image',
            ],
            [
                'attribute' => 'link',
                'format' => 'url',
                'headerOptions' => ['class' => 'column-200',],
                'filterOptions' => ['class' => 'column-200',],
                'contentOptions' => ['class' => 'column-200',],
            ],

            'alt',
            //'position',
            //'name',
            //'created',
            //'updated',

            ['class' => ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
