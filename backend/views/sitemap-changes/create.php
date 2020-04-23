<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapChanges */

$this->title = 'Create Sitemap Changes';
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Changes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitemap-changes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
