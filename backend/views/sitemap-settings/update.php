<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapSettings */

$this->title = 'Update Sitemap Settings: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sitemap-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
