<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\Broker */
/* @var $image common\models\records\Image */
/* @var $statuses common\models\records\BrokerStatus */
/* @var $bonuses  common\models\records\Bonus */
/* @var $languages common\models\records\Language */
/* @var $paymentMethods common\models\records\PaymentMethod */

$this->title = 'Create Broker';
$this->params['breadcrumbs'][] = ['label' => 'Brokers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'statuses' => $statuses,
        'bonuses' => $bonuses,
        'languages' => $languages,
        'paymentMethods' => $paymentMethods,
    ]) ?>

</div>
