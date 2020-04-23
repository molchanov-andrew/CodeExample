<?php

use common\models\records\LotteryTimer;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var LotteryTimer $model */
/* @var $form yii\widgets\ActiveForm */
/* @var $isModal bool */

if (!isset($isModal)) {
    $isModal = false;
}
?>

<div class="lottery-timer-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if ($isModal) : ?>
    <div class="modal-body"> <?php endif; ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'time')->textInput(['type' => 'time']) ?>

                <?= $form->field($model, 'timezone')->widget(Select2::class,
                    [
                        'data' => LotteryTimer::getAvailableTimezones(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'prompt' => 'Select timezone',
                        ],
                    ]
                ) ?>

                <?php if ($model->isNewRecord) : ?>

                    <?= $form->field($model, 'dayOfWeek')->dropDownList(
                        LotteryTimer::DAYS_OF_WEEK,
                        [
                            'multiple' => true,
                        ]
                    ) ?>

                <?php else : ?>

                    <?= $form->field($model, 'dayOfWeek')->dropDownList(
                        LotteryTimer::DAYS_OF_WEEK,
                        [
                            'options' => [
                                $model->dayOfWeek => ['selected' => true]
                            ],
                            'prompt' => 'Select day of week',
                        ]
                    ) ?>

                <?php endif; ?>
            </div>
            <div class="col-sm-6">
                <!--<? /*= $form->field($model, 'timeCorrection')->textInput(['maxlength' => true, 'type' => 'number']) */ ?>-->
                <!-- <? /*= $form->field($model, 'resultName')->textInput(['maxlength' => true]) */ ?>-->
                <?= $form->field($model, 'lotteryId')->hiddenInput()->label(false) ?>
            </div>
        </div>
        <?php if ($isModal) : ?> </div>
    <div class="modal-footer">
        <?php echo Html::submitButton('Save', [
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