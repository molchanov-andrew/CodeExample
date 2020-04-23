<?php use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JqueryAsset;

$form = ActiveForm::begin(['options' => ['class' => 'ajax-form']]); ?>
<div class="modal-body">
<div class="row">
    <?= Html::hiddenInput('fromAjax',1); ?>
    <?php foreach ($model->messages as $language => $message) : ?>
        <?= $form->field($model->messages[$language], '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textarea()->label($language) ?>
    <?php endforeach; ?>
</div>
</div>
<div class="modal-footer">
    <?php echo Html::submitButton('Change',[
        'class' => 'btn btn-success',
        'title' => 'Change timer',
    ]) ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?php $form::end(); ?>

<?php $this->registerJsFile('@web/js/ajaxForm.js', ['depends' => [JqueryAsset::class]]); ?>