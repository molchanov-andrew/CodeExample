<?php

use common\models\records\LotteryResult;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryResult */
/* @var $form yii\widgets\ActiveForm */
/* @var $lottery common\models\records\Lottery */
/* @var $lotteryList array common\models\records\Lottery */

if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="lottery-result-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <?= $form->field($model, 'uniqueResultId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mainNumbers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'additionalNumbers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bonusNumbers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([LotteryResult::STATUS_NOT_LOADED => 'Not loaded', LotteryResult::STATUS_WAITING_TO_LOAD => 'Waiting to load', LotteryResult::STATUS_LOADED => 'Loaded']) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'jackpot')->textInput() ?>

    <?= $form->field($model, 'lotteryId')->dropDownList(ArrayHelper::map($lotteryList, 'id', 'name'))->label('Lottery') ?>

    <?= $form->field($model, 'lotteryTimerId')->textInput() ?>

    <?php if($isModal) : ?> </div>
        <div class="modal-footer">
            <?php echo Html::submitButton('Change',[
                'class' => 'btn btn-success',
                'title' => 'Change timer',
            ]) ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    <?php else: ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
