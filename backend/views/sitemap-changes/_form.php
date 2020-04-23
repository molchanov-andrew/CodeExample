<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\SitemapChanges */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitemap-changes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList([ 'lottery_changed' => 'Lottery changed', 'broker_changed' => 'Broker changed', 'new_lottery' => 'New lottery', 'new_broker' => 'New broker', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastmod')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
