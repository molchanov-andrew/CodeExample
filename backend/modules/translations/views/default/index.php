<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;
use vintage\i18n\Module;
use vintage\i18n\models\Message;

/**
 * @var \yii\web\View $this
 * @var \vintage\i18n\models\search\SourceMessageSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Translations';
echo Breadcrumbs::widget(['links' => [$this->title]]);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="panel-body">
        <?php
        Pjax::begin();
        echo GridView::widget([
            'filterModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'id',
                    'value' => function ($model, $index, $dataColumn) {
                        return $model->id;
                    },
                    'filter' => false
                ],
                [
                    'attribute' => 'message',
                    'format' => 'raw',
                    'value' => function ($model, $index, $widget) {
                        return Html::a($model->message, ['update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                    },
                    'headerOptions' => ['class' => 'column-500',],
                    'filterOptions' => ['class' => 'column-500',],
                    'contentOptions' => ['class' => 'column-500',],
                ],
                [
                    'attribute' => 'translation',
                    'format' => 'raw',
                    'filter' => Html::activeDropDownList($searchModel, 'language', array_combine(array_values(Yii::$app->i18n->languages),Yii::$app->i18n->languages), ['class'=>'form-control','prompt' => 'Select language']) .
                            Html::activeInput('text', $searchModel, 'translation', ['class'=>'form-control']),
                    'value' => function ($model, $index, $widget) use ($searchModel) {
                        return $searchModel->language === null ? $model->getDefaultLangTranslation() : $model->customMessage->translation;
                    },
                    'headerOptions' => ['class' => 'column-500',],
                    'filterOptions' => ['class' => 'column-500',],
                    'contentOptions' => ['class' => 'column-500',],
                ],
                [
                    'attribute' => 'category',
                    'value' => function ($model, $index, $dataColumn) {
                        return $model->category;
                    },
                    'filter' => ArrayHelper::map($searchModel::getCategories(), 'category', 'category')
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model, $index, $widget) {
                        return Message::isModelFullyTranslated($model->id)
                            ? 'Translated'
                            : 'Not translated';
                    },
                    'filter' => Html::dropDownList($searchModel->formName() . '[status]', $searchModel->status, $searchModel->getStatus(), [
                        'class' => 'form-control',
                        'prompt' => ''
                    ])
                ]
            ]
        ]);
        Pjax::end();
        ?>
    </div>
</div>
