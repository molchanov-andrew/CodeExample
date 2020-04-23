<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\JuiAsset;
use yii\widgets\Pjax;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LotteryPositionToLanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lottery Position To Languages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-position-to-language-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::submitButton('Save',['class' => 'btn btn-primary', 'name'=>'savePositions', 'onclick' => <<<JS
    $('#savePositionsHidden').val(1);
    $('#positions').submit();
JS
]); ?>
    <?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(['id' => 'positions']); ?>
    <?= Html::hiddenInput('savePositions',0,['id' => 'savePositionsHidden']); ?>
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
                'value' => function(\common\models\records\Lottery $model)use($searchModel){
                    return Html::a($model->name,['/lottery/view','id' => $model->id]) .
                        Html::hiddenInput("LotteryPositionToLanguage[{$model->id}][lotteryId]",$model->id) . Html::hiddenInput("LotteryPositionToLanguage[{$model->id}][languageId]",$searchModel->languageId);
                }
            ],
            [
                'attribute' => 'Position',
                'format' => 'raw',
                'value' => function(\common\models\records\Lottery $model){
                    return Html::input('text',"LotteryPositionToLanguage[{$model->id}][position]",$model->lotteryPositionToLanguage !== null ? $model->lotteryPositionToLanguage->position : '',['class' => 'form-control change-current-position']);
                },
                'headerOptions' => ['class' => 'column-50',],
                'filterOptions' => ['class' => 'column-50',],
                'contentOptions' => ['class' => 'column-50',],
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['id' => $model['id'], 'class' => 'sortable-item'];
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