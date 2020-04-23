<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\ContactMessages */

$this->title = 'Update Contact Messages: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contact Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contact-messages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
