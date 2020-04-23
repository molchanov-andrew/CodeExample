<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\LotterySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lottery-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'published') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'jackpot') ?>

    <?= $form->field($model, 'systematic') ?>

    <?php // echo $form->field($model, 'mainNumbers') ?>

    <?php // echo $form->field($model, 'mainNumbersToCheck') ?>

    <?php // echo $form->field($model, 'mainNumbersDescription') ?>

    <?php // echo $form->field($model, 'addNumbers') ?>

    <?php // echo $form->field($model, 'addNumbersToCheck') ?>

    <?php // echo $form->field($model, 'addNumbersDescription') ?>

    <?php // echo $form->field($model, 'chanceToWin') ?>

    <?php // echo $form->field($model, 'overallChance') ?>

    <?php // echo $form->field($model, 'numberAmounts') ?>

    <?php // echo $form->field($model, 'logoImageId') ?>

    <?php // echo $form->field($model, 'parentLotteryId') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
