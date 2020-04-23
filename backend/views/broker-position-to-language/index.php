<?php

use yii\bootstrap\ActiveForm;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\JuiAsset;
use yii\widgets\Pjax;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BrokerPositionToLanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Broker Position To Languages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-position-to-language-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::submitButton('Save',['class' => 'btn btn-primary', 'name'=>'savePositions', 'onclick' => <<<JS
    $('#savePositionsHidden').val(1);
    $('#positions').submit();
JS
    ]); ?>
    <?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['id' => 'positions']); ?>
    <?= Html::hiddenInput('savePositions',0); ?>
    <?= GridView::widget([
        'id' => 'sortable-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_HEADER,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'filter' => '<label>Language</label>' . Select2::widget([
                    'data' => array_column($languages,'name','id'),
                    'model' => $searchModel,
                    'value' => $searchModel->languageId,
                    'name' => $searchModel->formName() . '[languageId]',
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]),
                'value' => function(\common\models\records\Broker $model)use($searchModel){
                    return Html::a($model->name,['/broker/view','id' => $model->id]) .
                        Html::hiddenInput("BrokerPositionToLanguage[{$model->id}][brokerId]",$model->id) . Html::hiddenInput("BrokerPositionToLanguage[{$model->id}][languageId]",$searchModel->languageId);
                }
            ],
            [
                'attribute' => 'Position',
                'format' => 'raw',
                'value' => function(\common\models\records\Broker $model){
                    return Html::input('text',"BrokerPositionToLanguage[{$model->id}][position]",$model->brokerPositionToLanguage !== null ? $model->brokerPositionToLanguage->position : '',['class' => 'form-control change-current-position']);
                },
                'headerOptions' => ['class' => 'column-50',],
                'filterOptions' => ['class' => 'column-50',],
                'contentOptions' => ['class' => 'column-50',],
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {
            $positiveClass = $model->status->isPositive ? 'status-positive' : '';
            return ['id' => $model['id'], 'class' => 'sortable-item ' . $positiveClass];
        },
    ]); ?>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
    <?= Html::submitButton('Save',['class' => 'btn btn-primary', 'name'=>'savePositions', 'onclick' => <<<JS
    $('#savePositionsHidden').val(1);
    $('#positions').submit();
JS
    ]); ?>
</div>

<?php $this->registerJsFile('@web/js/sortablePositions.js',['depends' => [\yii\web\JqueryAsset::class, JuiAsset::class]]); ?>