<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\ContactMessages */

$this->title = 'Create Contact Messages';
$this->params['breadcrumbs'][] = ['label' => 'Contact Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-messages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
