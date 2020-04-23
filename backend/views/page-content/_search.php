<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\PageContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'keywords') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'additionalDescription') ?>

    <?php // echo $form->field($model, 'alternativeDescription') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'published') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'imageId') ?>

    <?php // echo $form->field($model, 'languageId') ?>

    <?php // echo $form->field($model, 'pageId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
