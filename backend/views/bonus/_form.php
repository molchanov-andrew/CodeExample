<?php

use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use backend\widgets\translations\Translate;

/* @var $this yii\web\View */
/* @var $model common\models\records\Bonus */
/* @var $form yii\widgets\ActiveForm */

if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="bonus-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>

    <?= $form->field($model, 'name',[
            'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->name, 'category' => \common\models\records\SourceMessage::CATEGORY_BONUSES],
        ])->textInput(['maxlength' => true]) ?>


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
