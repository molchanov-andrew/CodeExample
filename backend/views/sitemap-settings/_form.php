<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitemap-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'area')->dropDownList([ 'all' => 'All', 'by_module' => 'By module', 'by_entity' => 'By entity', 'yearly_results' => 'Yearly results', 'monthly_results' => 'Monthly results', 'daily_results' => 'Daily results', 'results_days_ago' => 'Results days ago', 'results_years_ago' => 'Results years ago', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'areaParameter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'changefreq')->dropDownList([ 'always' => 'Always', 'hourly' => 'Hourly', 'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly', 'never' => 'Never', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <?= $form->field($model, 'lastmod')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
