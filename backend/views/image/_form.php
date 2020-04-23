<?php

use common\models\records\Image;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\Image */
/* @var $form yii\widgets\ActiveForm */
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="image-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <?= $form->field($model, 'fileName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList(Image::getCategoryList(),[
        'options' => [
            $model->category => ['selected' => true],
        ],
    ]) ?>

    <?= $form->field($model,'file')->fileInput(); ?>

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
<?php $this->registerJsFile('@web/js/copyImageNameOnLoad.js', ['depends' => [\yii\web\JqueryAsset::class]]); ?>