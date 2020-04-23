<?php

use backend\widgets\image\Select2Image;
use kartik\select2\Select2;
use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use backend\widgets\translations\Translate;

/* @var $this yii\web\View */
/* @var $model common\models\records\Lottery */
/* @var $form yii\widgets\ActiveForm */
/* @var $countries array common\models\records\Country */

if (!isset($isModal)) {
    $isModal = false;
}
?>

<div class="lottery-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if ($isModal) : ?>
    <div class="modal-body"> <?php endif; ?>
        <div class="row">
            <div class="col-sm-6">
                <h3>Main info</h3>
                <?php if (!$isModal) : ?>
                    <?= $form->field($model, 'name', [
                        'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->name, 'category' => \common\models\records\SourceMessage::CATEGORY_LOTTERIES],
                    ])->textInput(['maxlength' => true]) ?>
                <?php endif; ?>
                <?= $form->field($model, 'published')->checkbox() ?>

                <?= $form->field($model, 'jackpot')->textInput() ?>

                <?= $form->field($model, 'cost')->textInput() ?>

                <?= $form->field($model, 'countryId')->widget(Select2::class, [
                    'data' => array_column($countries, 'name', 'id'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'prompt' => 'None',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Country') ?>

                <?php if (isset($model->logoImage->filePath)) echo Html::img(Yii::$app->params['frontendUrl'] . $model->logoImage->filePath, ['alt' => 'Lottery Image']); ?>
                <?= $form->field($model, 'lotteryImage')->fileInput();?>
            </div>
            <div class="col-sm-6">
                <h3>Others</h3>
                <?= $form->field($model, 'chanceToWin')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'overallChance')->textInput() ?>

                <!--<?= $form->field($model, 'numberAmounts')->textInput(['maxlength' => true]) ?>-->

                <!--<?/*= $form->field($model, 'parentLotteryId')->widget(Select2::class, [
                    'data' => array_column($lotteries, 'name', 'id'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'prompt' => 'None',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Parent Lottery') */?>-->
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <h3>Main numbers data</h3>

                <?= $form->field($model, 'mainNumbers')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'mainNumbersToCheck')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'mainNumbersDescription', [
                    'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->mainNumbersDescription, 'category' => \common\models\records\SourceMessage::CATEGORY_LOTTERIES],
                ])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <h3>Additional numbers data</h3>

                <?= $form->field($model, 'addNumbers')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'addNumbersToCheck')->textInput(['maxlength' => true]) ?>

                <p style="cursor: pointer; text-decoration: underline;"
                   onclick="$('#lottery-addnumbersdescription').val($(this).find('span').html())">Default: <span>remaining numbers<br>not accountable for jackpot</span>
                </p>
                <?= $form->field($model, 'addNumbersDescription', [
                    'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->addNumbersDescription, 'category' => \common\models\records\SourceMessage::CATEGORY_LOTTERIES],
                ])->textInput(['maxlength' => true]) ?>

            </div>
        </div>

        <?php if ($isModal) : ?> </div>
    <div class="modal-footer">
        <?php echo Html::submitButton('Change', [
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
