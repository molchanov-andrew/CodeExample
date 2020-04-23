<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapSettings */

$this->title = 'Create Sitemap Settings';
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitemap-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
