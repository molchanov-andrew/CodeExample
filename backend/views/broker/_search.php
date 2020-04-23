<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\BrokerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="broker-search">

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

    <?= $form->field($model, 'site') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'clicks') ?>

    <?php // echo $form->field($model, 'minimalDeposit') ?>

    <?php // echo $form->field($model, 'disableIframe') ?>

    <?php // echo $form->field($model, 'systematic') ?>

    <?php // echo $form->field($model, 'scanTicket') ?>

    <?php // echo $form->field($model, 'chat') ?>

    <?php // echo $form->field($model, 'security') ?>

    <?php // echo $form->field($model, 'support') ?>

    <?php // echo $form->field($model, 'gameplay') ?>

    <?php // echo $form->field($model, 'promotions') ?>

    <?php // echo $form->field($model, 'withdrawals') ?>

    <?php // echo $form->field($model, 'usability') ?>

    <?php // echo $form->field($model, 'gameSelection') ?>

    <?php // echo $form->field($model, 'discounts') ?>

    <?php // echo $form->field($model, 'marks') ?>

    <?php // echo $form->field($model, 'summaryMarks') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'statusId') ?>

    <?php // echo $form->field($model, 'imageId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
