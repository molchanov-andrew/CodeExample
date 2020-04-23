<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\models\records\Image;

/* @var $this yii\web\View */
/* @var $model common\models\records\Lottery */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lottery-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'published',
            'name',
            'jackpot',
            'cost',
            'mainNumbers',
            'mainNumbersToCheck',
            'mainNumbersDescription',
            'addNumbers',
            'addNumbersToCheck',
            'addNumbersDescription',
            'chanceToWin',
            'overallChance',
            'numberAmounts',
            [
                'format' => ['image', ['width' => '100px']],
                'label' => 'Logo Image',
                'value' => Yii::$app->params['frontendUrl'] . $model->getLogoImage()->one()->getFilePath()
            ],
            [
                'label' => 'Parent Lottery',
                'value' => function ($model) {
                    if ($model->getParentLottery()->one()) return $model->getParentLottery()->one()->name;
                    else return;
                }],
            [
                'label' => 'Country',
                'value' => $model->getCountry()->one()->name,
            ],
            'created',
            'updated',
        ],
    ]) ?>

</div>
