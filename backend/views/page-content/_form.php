<?php

use backend\widgets\image\Select2Image;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use backend\widgets\froala\editor\src\FroalaEditorWidget;
use kartik\select2\Select2;
use backend\widgets\image\Select2Banner;

/* @var $this yii\web\View */
/* @var $model common\models\records\PageContent */
/* @var $form yii\widgets\ActiveForm */
/* @var \common\models\records\Image[] $images */
/* @var \common\models\records\Page $page */
/* @var \common\models\records\Language[] $languages */
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="page-content-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <div class="row">
        <div class="col-sm-6">
            <h3>Main data</h3>

            <div class="form-group field-pagecontent-url">
                <label class="control-label"  for="#pagecontent-url">Url</label>
                <div class="input-group">
                    <div class="input-group-addon">/</div>
                    <?= $form->field($model, 'url',['options' => ['tag' => null,
                        'class' => 'form-control',
                        'id' => 'pagecontent-url',]])->textInput(['maxlength' => true, ])->label(false) ?>
                </div>
            </div>

            <?= $form->field($model, 'published')->checkbox() ?>

            <?= $form->field($model, 'languageId')->widget(Select2::class, [
                'data' => array_column($notUsedLanguages,'name','id'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>

            <?= $form->field($model, 'pageId')->hiddenInput()->label(false) ?>

            <h3>SEO data</h3>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <h3>Other data</h3>

            <?= $form->field($model, 'additionalDescription')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'alternativeDescription')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'imageId')->widget(Select2Image::class,[
                'data' => $images,
                'theme' => Select2Image::THEME_BOOTSTRAP,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'content')->widget(FroalaEditorWidget::class,[
                'options' => [
                    'class' => 'froala-text-editor',
                ],
                'clientOptions' => Yii::$app->params['froalaOptions']
            ]) ?>

            <?php foreach ($banners as $position => $bannersArray) : ?>
            <?= Html::label("Banner {$position}"); ?>
                <?= Select2Banner::widget([
                    'name' => "PageContent[banners][{$position}]",
                    'data' => $bannersArray,
                    'value' => $model->banners[$position]->id ?? null,
                    'theme' => Select2Banner::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
            <?php endforeach; ?>
        </div>
    </div>


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
<?php
$script = <<< JS
    $(document).ready(function(){
        $('#pagecontent-url').keyup(function () {
            $(this).val(removeSlashInBeginning($(this).val()));
            })
    });        
    function removeSlashInBeginning(s) {
        return (s.length && s[0] == '/') ? s.slice(1) : s;
    }    
JS;
$this->registerJs($script);
?>
