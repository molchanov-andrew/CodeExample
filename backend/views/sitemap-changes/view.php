<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapChanges */

$this->title = $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitemap-changes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'type' => $model->type, 'identifier' => $model->identifier], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'type' => $model->type, 'identifier' => $model->identifier], [
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
            'type',
            'identifier',
            'lastmod',
        ],
    ]) ?>

</div>
