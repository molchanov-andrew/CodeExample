<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryResult */

$this->title = 'Create Lottery Result';
$this->params['breadcrumbs'][] = ['label' => 'Lottery Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lottery' => $lottery,
    ]) ?>

</div>
