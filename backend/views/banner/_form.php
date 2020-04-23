<?php

use backend\models\widgets\bootstrap\ActiveForm;
use backend\widgets\image\Select2Image;
use common\models\records\Banner;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\Banner */
/* @var $form yii\widgets\ActiveForm */
/* @var $images \common\models\records\Image[] */


if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(['enableClientScript' => true]); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->dropDownList(Banner::getPositionList(),[
        'options' => [
            $model->position => ['selected' => true],
        ],
    ]) ?>


    <?= $form->field($model, 'imageId')->widget(Select2Image::class,[
        'data' => $images,
        'theme' => Select2Image::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'options' => [
            'class' => 'select2-banner-widget',
        ]
    ]) ?>

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
