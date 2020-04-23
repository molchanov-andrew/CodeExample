<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = array_merge($menuItems,[
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Lotteries', 'url' => ['/lottery/index']],
            ['label' => 'Brokers', 'url' => ['/broker/index']],
            ['label' => 'Pages', 'url' => ['/page/index']],
            ['label' => 'Settings', 'url' => ['/setting/index']],
            ['label' => 'Others',
                'items' => [
                    ['label' => 'DB manager', 'url' => ['/db-manager/default/index']],
                    ['label' => 'Translations', 'url' => ['/translations']],
                    ['label' => 'Language', 'url' => ['/language/index']],
                    ['label' => 'Image', 'url' => ['/image/index']],
                    ['label' => 'Banner', 'url' => ['/banner/index']],
                    ['label' => 'Country', 'url' => ['/country/index']],
                    ['label' => 'Currency', 'url' => ['/currency/index']],
                    ['label' => 'PaymentMethod', 'url' => ['/payment-method/index']],
                    ['label' => 'ContactMessages', 'url' => ['/contact-messages/index']],
                    ['label' => 'Subscribe', 'url' => ['/subscribe/index']],
                    ['label' => 'Slider', 'url' => ['/slider/index']],
                    ['label' => 'User', 'url' => ['/user/index']],
                    ['label' => 'SitemapChanges', 'url' => ['/sitemap-changes/index']],
                    ['label' => 'SitemapSettings', 'url' => ['/sitemap-settings/index']],
                ]
            ],
            [
                'label' => 'Broker-Lottery Data',
                'items' => [
                    ['label' => 'Bonus', 'url' => ['/bonus/index']],
                    ['label' => 'BrokerStatus', 'url' => ['/broker-status/index']],
                    ['label' => 'BrokerPositionToLanguage', 'url' => ['/broker-position-to-language/index']],
                    ['label' => 'LotteryPositionToLanguage', 'url' => ['/lottery-position-to-language/index']],
                ]
            ],
        ]);
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php
yii\bootstrap\Modal::begin([
    'id'=>'modalGeneral',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => true,
    ],
    'options' => ['tabindex' => '-1'],
]);
yii\bootstrap\Modal::end();
?>
<div id="generalAlert" class="alert"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
