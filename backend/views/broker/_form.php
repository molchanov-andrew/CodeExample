<?php

use backend\widgets\image\Select2Image;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\widgets\translations\Translate;

/* @var $this yii\web\View */
/* @var $model common\models\records\Broker */
/* @var $image common\models\records\Image */
/* @var $statuses common\models\records\BrokerStatus */
/* @var $bonuses  common\models\records\Bonus */
/* @var $languages common\models\records\Language */
/* @var $paymentMethods common\models\records\PaymentMethod */

if(!isset($isModal)){
    $isModal = false;
}
?>

<div class="broker-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>
    <div class="row">
        <div class="col-sm-6">
            <h3>Main info</h3>

            <?= $form->field($model, 'name',[
                'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->name, 'category' => \common\models\records\SourceMessage::CATEGORY_BROKERS],
            ])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'published')->checkbox() ?>

            <?= $form->field($model, 'site',[
                'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->site, 'category' => \common\models\records\SourceMessage::CATEGORY_BROKER_LINKS],
            ])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'statusId')->dropDownList(array_column($statuses,'name','id'),[
                'options' => [
                    $model->statusId => ['selected' => true],
                ],
            ]) ?>

            <?= $form->field($model, 'imageId')->widget(Select2Image::class,[
                'data' => $images,
                'theme' => Select2Image::THEME_BOOTSTRAP,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <h3>Custom parameters</h3>

<!--            --><?php //if($model->isNewRecord === false) : ?>
                <?= $form->field($model, 'languages')->widget(Select2::class,[
                    'data' => !empty($languages) ? array_column($languages,'name','id') : [],
                    'value' => !empty($model->languages) ? array_column($model->languages,'id') : [],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => 'Related languages',
                        'multiple' => true,
                    ]
                ]) ?>
                <?= $form->field($model, 'bonuses')->widget(Select2::class,[
                    'data' => !empty($bonuses) ? array_column($bonuses,'name','id') : [],
                    'value' => !empty($model->bonuses) ? array_column($model->bonuses,'id') : [],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => 'Related bonuses',
                        'multiple' => true,
                    ]
                ]) ?>
                <?= $form->field($model, 'paymentMethods')->widget(Select2::class,[
                    'data' => !empty($paymentMethods) ? array_column($paymentMethods,'name','id') : [],
                    'value' => !empty($model->paymentMethods) ? array_column($model->paymentMethods,'id') : [],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => 'Related payment methods',
                        'multiple' => true,
                    ]
                ]) ?>
<!--            --><?php //endif; ?>

            <?= $form->field($model, 'clicks')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'year')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'minimalDeposit')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'disableIframe')->checkbox() ?>

            <?= $form->field($model, 'syndicat')->checkbox() ?>

            <?= $form->field($model, 'systematic')->checkbox() ?>

            <?= $form->field($model, 'scanTicket')->checkbox() ?>

            <?= $form->field($model, 'chat')->checkbox() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <h3>Ratings</h3>
            <?= $form->field($model, 'security')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'support')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'gameplay')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'promotions')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'withdrawals')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'usability')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'gameSelection')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>

            <?= $form->field($model, 'discounts')->textInput(['type' => 'range', 'min' => 0, 'max' => 10]) ?>
        </div>

        <div class="col-sm-6">
            <h3>User related data</h3>

            <?= $form->field($model, 'marks')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'summaryMarks')->textInput(['type' => 'number']) ?>
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
