<?php

use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use common\models\records\Image;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\CustomCheckboxColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Image', ['create'], ['class' => 'btn btn-success']) ?>
<!--        --><?//= Html::a('Change multiple',['change-multiple'],[
//            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
//            'data-toggle'=>'modal',
//            'data-target'=>'#modalGeneral',
//            'data-pjax' => '0',
//        ]); ?>
        <?= Html::a('Delete', ['delete-multiple'], ['class' => 'btn btn-danger ajax-solo-rows',
            'data-solo-confirm' => Yii::t('yii', 'Are you sure you want to delete this items?'),]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => CustomCheckboxColumn::class, 'limitFilter' => true],
            ['class' => SerialColumn::class],

            'id',
            'fileName',
            [
                'attribute' => 'category',
                'filter' => Html::activeDropDownList($searchModel, 'category',
                    Image::getCategoryList(),
                    ['class'=>'form-control','prompt' => 'Select format']),
                'value' => function(Image $model){
                    return $model->categoryName;
                }
            ],
            ['class' => \backend\models\image\ImageColumn::class],
            'created',
            //'modified',

            ['class' => ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
