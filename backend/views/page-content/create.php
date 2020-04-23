<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\PageContent */

$this->title = 'Create Page Content';
$this->params['breadcrumbs'][] = ['label' => 'Page Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content-create">

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
