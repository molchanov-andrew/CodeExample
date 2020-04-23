<?php

use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\ContactMessages */
/* @var $form yii\widgets\ActiveForm */
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="contact-messages-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>

    <?= $form->field($model, 'siteName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'languageIso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isRead')->textInput() ?>

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
