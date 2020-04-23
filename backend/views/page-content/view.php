<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\PageContent */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Page Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'languageId' => $model->languageId, 'pageId' => $model->pageId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'languageId' => $model->languageId, 'pageId' => $model->pageId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'url:url',
            'title',
            'keywords',
            'description:ntext',
            'additionalDescription:ntext',
            'alternativeDescription:ntext',
            'content:ntext',
            'published',
            'created',
            'updated',
            'imageId',
            'languageId',
            'pageId',
        ],
    ]) ?>

</div>
