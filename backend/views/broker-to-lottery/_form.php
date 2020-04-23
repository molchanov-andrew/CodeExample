<?php

use yii\helpers\Html;
use backend\models\widgets\bootstrap\ActiveForm;
use backend\widgets\translations\Translate;

/* @var $this yii\web\View */
/* @var $model common\models\records\BrokerToLottery */
/* @var $form yii\widgets\ActiveForm */
/** @var \common\models\records\Broker[]|array $brokers */
/** @var \common\models\records\Lottery[]|array $lotteries */

if(!isset($isModal)){
    $isModal = false;
}
?>
<?php $parentEntity = Yii::$app->request->get('parentEntity',null); ?>
<div class="broker-to-lottery-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if($isModal) : ?> <div class="modal-body"> <?php endif; ?>

    <!--<?php if($parentEntity === 'broker') : ?>
        <?= $form->field($model, 'brokerId')->hiddenInput()->label(false) ?>
    <?php else : ?>
        <?php $brokersList = (!$model->isNewRecord && $model->broker !== null) ? array_replace(array_column($brokers,'name','id'),[$model->broker->id => $model->broker->name]) : array_column($brokers,'name','id'); ?>
        <?= $form->field($model, 'brokerId')->dropDownList($brokersList,[
            'options' => [
                $model->brokerId => ['selected' => true],
            ],
        ])->label('Broker') ?>
    <?php endif; ?>-->

    <?php if($parentEntity === 'lottery') : ?>
        <?= $form->field($model, 'lotteryId')->hiddenInput()->label(false) ?>
    <?php else : ?>
        <?php $lotteriesList = (!$model->isNewRecord && $model->lottery !== null) ? array_replace(array_column($lotteries,'name','id'),[$model->lottery->id => $model->lottery->name]) : array_column($lotteries,'name','id'); ?>
        <?= $form->field($model, 'lotteryId')->dropDownList($lotteriesList,[
            'options' => [
                $model->lotteryId => ['selected' => true],
            ],
        ])->label('Lottery') ?>
    <?php endif; ?>

    <?= $form->field($model, 'syndicat')->checkbox() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'url',[
            'translationAddon' => $model->isNewRecord || $isModal ? null : ['message' => $model->url, 'category' => \common\models\records\SourceMessage::CATEGORY_BROKER_TO_LOTTERY_LINK],
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
