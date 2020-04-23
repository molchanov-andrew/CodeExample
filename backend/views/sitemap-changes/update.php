<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapChanges */

$this->title = 'Update Sitemap Changes: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['view', 'type' => $model->type, 'identifier' => $model->identifier]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sitemap-changes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
