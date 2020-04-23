<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\SitemapSettingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitemap-settings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'area') ?>

    <?= $form->field($model, 'areaParameter') ?>

    <?= $form->field($model, 'changefreq') ?>

    <?= $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'lastmod') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
