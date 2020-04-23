<?php

use yii\grid\ActionColumn;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\CustomCheckboxColumn;
use backend\models\image\RelativeImageColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Country', ['create'], ['class' => 'btn btn-success']) ?>
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
            'iso',
            [
                'label' => 'Currency',
                'attribute' => 'currencyId',
                'format' => 'raw',
                'filter' => Html::activeInput('text', $searchModel, 'currencyName', ['class'=>'form-control']),
                'value' => function(\common\models\records\Country $model){
                    return Html::a($model->currency->name,['currency/view','id' => $model->currency->id]);
                }
            ],
            [
                'label' => 'Language',
                'attribute' => 'languageId',
                'filter' => Html::activeInput('text', $searchModel, 'languageName', ['class'=>'form-control']),
                'value' => function(\common\models\records\Country $model){
                    return $model->language->name;
                }
            ],
            [
                'class' => RelativeImageColumn::class,
                'imageField' => 'image',
            ],

            ['class' => ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
