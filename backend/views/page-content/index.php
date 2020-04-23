<?php

use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\ActionColumn;
use backend\models\grid\CustomCheckboxColumn;
use common\models\records\PageContent;
use common\models\records\Page;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Page Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if($page !== null && !empty($page->getNotUsedLanguages())) : ?>
            <?= Html::a('Create Page Content', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
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
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function(PageContent $model, $key, $url) use ($page) {
                    $keyPair = http_build_query($key);
                    return Html::a($model->url, ($page === null) ? "/page/{$model->page->id}/page-content/update?{$keyPair}" : "/page/{$page->id}/page-content/update?{$keyPair}");
                }
//            http://localotto.admin.local/page/4/page-content/update/languageId=1&pageId=4
//            http://localotto.admin.local/page/4/page-content/update?languageId=1&pageId=4

            ],
            [
                'attribute' => 'title',
                'headerOptions' => ['class' =>'column-300'],
                'filterOptions' => ['class' =>'column-300'],
                'contentOptions' => ['class' =>'column-300'],
            ],
            [
                'attribute' => 'pageModule',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'module',
                    Page::MODULES,
                    ['class'=>'form-control','prompt' => 'Select module']),
                'value' => function(PageContent $model){
                    return $model->page->moduleName;
                }
            ],
            [
                'attribute' => 'Language',
                'filter' => Html::activeInput('text', $searchModel, 'languageName', ['class'=>'form-control']),
                'value' => function(PageContent $model){
                    return $model->language->name;
                }
            ],
            'updated',
            //'imageId',

            //'pageId',

            [
                'class' => ActionColumn::class,
                'filter' => Html::activeCheckbox($searchModel,'notPublished',[]),
                'urlCreator' => function ($action, $model, $key, $index) use ($page) {
                    /** @var PageContent $model */
                    $keyPair = http_build_query($key);
                    return $page === null ? "/page/{$model->page->id}/page-content/{$action}?{$keyPair}" : "/page/{$page->id}/page-content/{$action}?{$keyPair}";
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
