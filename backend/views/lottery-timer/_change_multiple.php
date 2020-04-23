<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
?>
    <div class="change-multiple-modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Change multiple lottery-timers</h4>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'isModal' => true,
        ]) ?>
    </div>
<?php //$this->registerJsFile('@web/js/toggleDisabled.js', ['depends' => [\yii\web\JqueryAsset::class]]); ?>
<?php //$this->registerCssFile('@web/css/gridChangeMultiple.css'); ?>
