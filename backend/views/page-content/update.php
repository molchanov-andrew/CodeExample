<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\PageContent */

$this->title = 'Update Page Content: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Page Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'languageId' => $model->languageId, 'pageId' => $model->pageId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'languages' => $languages,
        'page' => $page,
        'notUsedLanguages' => $notUsedLanguages,
        'banners' => $banners,
    ]) ?>

</div>
