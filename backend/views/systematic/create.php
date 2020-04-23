<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\Systematic */

$this->title = 'Create Systematic';
$this->params['breadcrumbs'][] = ['label' => 'Systematics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="systematic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
