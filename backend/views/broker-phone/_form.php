<?php

use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerPhone */
/* @var $form yii\widgets\ActiveForm */
/* @var $countries \common\models\records\Country[]*/
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="broker-phone-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>

    <?= $form->field($model, 'brokerId')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'countryId')->dropDownList(array_column($countries,'name','id'),[
        'options' => [
            $model->countryId => ['selected' => true],
        ],
    ])->label('Country') ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

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
