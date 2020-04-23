<?php

use common\models\records\Broker;
use common\models\records\Lottery;
use common\models\records\PageContent;
use yii\bootstrap\Html;
/* @var $this yii\web\View */
/* @var $lotteries Lottery[] */
/* @var $lotteriesCount integer */
/* @var $brokers Broker[] */
/* @var $brokersCount integer */
/* @var $pageContents PageContent[] */
/* @var $pageCount integer */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="row">
        <div class="col-sm-12">
            <h1>Welcome to localotto admin panel!</h1>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 home-items-container">
            <h3> <?= Html::tag('span', $lotteriesCount) . Html::a('   Lotteries',['lottery/index']); ?></h3>
            <h4>Last updated </h4>
            <?php foreach ($lotteries as $item) : ?>
                <div class="row home-item">
                    <div class="col-sm-8 home-item-link"><?= Html::a($item->name,['lottery/view','id' => $item->id]); ?></div>
                    <div class="col-sm-4"><?= $item->updated ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-sm-4 home-items-container">
            <h3><?= Html::tag('span', $brokersCount) . Html::a('   Brokers',['broker/index']); ?> </h3>
            <h4>Last updated</h4>
            <?php foreach ($brokers as $item) : ?>
                <div class="row home-item">
                    <div class="col-sm-8 home-item-link"><?= Html::a($item->name,['broker/view','id' => $item->id]); ?></div>
                    <div class="col-sm-4"><?= $item->updated ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-sm-4 home-items-container">
            <h3><?= Html::tag('span', $pageCount) . Html::a('   Pages',['page/index']); ?> </h3>
            <h4>Last updated</h4>
            <?php foreach ($pageContents as $item) : ?>
                <div class="row home-item">
                    <div class="col-sm-8 home-item-link"><?= Html::a("{$item->page->name} : {$item->language->iso}",['/page/' . $item->page->id . '/page-content/update','pageId' => $item->pageId,'languageId' => $item->languageId]); ?></div>
                    <div class="col-sm-4"><?= $item->updated ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::a('Flush cache',['cache/flush'],['class' => 'btn btn-default ajax-solo flush-cache-button']); ?>
        </div>
    </div>
</div>
