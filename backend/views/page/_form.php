<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\records\Page */
/* @var $form yii\widgets\ActiveForm */
/* @var $brokers \common\models\records\Broker[]*/
/* @var $lotteries \common\models\records\Lottery[]*/
/* @var $countries \common\models\records\Country[]*/
if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'module')->widget(Select2::class, [
                'data' => \common\models\records\Page::MODULES,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'promotingBrokerId')->widget(Select2::class, [
                'data' => array_column($brokers,'name','id'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>

            <?= $form->field($model, 'brokerId')->widget(Select2::class, [
                'data' => array_column($brokers,'name','id'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>

            <?= $form->field($model, 'lotteryId')->widget(Select2::class, [
                'data' => array_column($lotteries,'name','id'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>

            <?= $form->field($model, 'countryId')->widget(Select2::class, [
                'data' => array_column($countries,'name','id'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => 'None',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
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
