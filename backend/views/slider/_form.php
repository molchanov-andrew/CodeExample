<?php

use backend\widgets\image\Select2Image;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\Slider */
/* @var $form yii\widgets\ActiveForm */
/* @var \common\models\records\Image[] $images */
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="slider-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <?= $form->field($model, 'languageId')->dropDownList(array_column($languages,'name','id'),[
        'options' => [
            $model->languageId => ['selected' => true],
        ],
    ]) ?>

    <?= $form->field($model, 'imageId')->widget(Select2Image::class,[
        'data' => $images,
        'theme' => Select2Image::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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
