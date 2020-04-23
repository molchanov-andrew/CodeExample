<?php
/**
 * Date: 5/22/18
 */

namespace backend\controllers;

use backend\models\MigrateOldData;
use common\models\records\Image;
use yii\db\Connection;
use yii\web\Controller;

/* @property Connection $db */
class MigratingOldDataController extends Controller
{
    public function actionAll()
    {
        $time_start = microtime(true);
        /** time counting began */
        $migrationModel = new MigrateOldData();
//        $migrationModel->migratePaymentMethods();
//        echo '<p>Migrated. migratePaymentMethods</p>';
//        $migrationModel->migrateLanguages();
//        echo '<p>Migrated Languages</p>';
        $migrationModel->migrateCurrencies();
        echo '<p>Migrated. migrateCurrencies</p>';
        $migrationModel->migrateCountries();
        echo '<p>Migrated. migrateCountries</p>';
        $migrationModel->migrateLotteries();
        echo '<p>Migrated. migrateLotteries</p>';
        $migrationModel->migrateBrokerStatuses();
        echo '<p>Migrated migrateBrokerStatuses</p>';
        $migrationModel->migrateBrokers();
        echo '<p>Migrated. migrateBrokers</p>';
        $migrationModel->migratePages();
        echo '<p>Migrated Pages</p>';
        $migrationModel->migrateBanners();
        echo '<p>Migrated Banners</p>';
        $migrationModel->migrateBonuses();
        echo '<p>Migrated migrateBonuses</p>';
        $migrationModel->migrateBrokerEmails();
        echo '<p>Migrated migrateBrokerEmails</p>';
        $migrationModel->migrateBrokerLanguages();
        echo '<p>Migrated migrateBrokerLanguages</p>';
        $migrationModel->migrateBrokerLanguagePositions();
        echo '<p>Migrated migrateBrokerLanguagePositions</p>';
        $migrationModel->migrateLotteryLanguagePositions();
        echo '<p>Migrated migrateLotteryLanguagePositions</p>';
        $migrationModel->migrateBrokerPaymentMethods();
        echo '<p>Migrated migrateBrokerPaymentMethods</p>';
        $migrationModel->migrateBrokerPhone();
        echo '<p>Migrated migrateBrokerPhone</p>';
        $migrationModel->migrateBrokerToLottery();
        echo '<p>Migrated migrateBrokerToLottery</p>';
        $migrationModel->migrateContactMessages();
        echo '<p>Migrated migrateContactMessages</p>';
        $migrationModel->migrateSubscribe();
        echo '<p>Migrated migrateSubscribe</p>';
        $migrationModel->migrateSitemapChanges();
        echo '<p>Migrated migrateSitemapChanges</p>';
        $migrationModel->migrateSitemapSettings();
        echo '<p>Migrated migrateSitemapSettings</p>';
        $migrationModel->migrateSlider();
        echo '<p>Migrated migrateSlider</p>';
//        $migrationModel->migrateI18n();
//        echo '<p>Migrated migrateI18n</p>';
        /** time counting finished */
        $time_end = microtime(true);
        $execString = "<br>Execution time: ".round(($time_end - $time_start),4)." ms. \n <br>";
        echo $execString;
    }

    public function actionPm()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migratePaymentMethods();
        echo 'Migrated. migratePaymentMethods';
    }

    public function actionCurrencies()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateCurrencies();
        echo 'Migrated. migrateCurrencies';
    }

    public function actionCountries()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateCountries();
        echo 'Migrated. migrateCountries';
    }

    public function actionLotteries()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateLotteries();
        echo 'Migrated. migrateLotteries';
    }

    public function actionLotteryTimers()
    {
        // Or maybe get from minilotto
    }

    public function actionLotteryResults()
    {

    }

    public function actionBrokers()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokers();
        echo 'Migrated. migrateBrokers';
    }

    public function actionBrokerStatuses()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerStatuses();
        echo 'Migrated migrateBrokerStatuses';
    }
    public function actionLanguages()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateLanguages();
        echo 'Migrated Languages';
    }

    public function actionPages()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migratePages();
        echo 'Migrated Pages';
    }

    public function actionBanners()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBanners();
        echo 'Migrated Banners';
    }

    public function actionBonuses()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBonuses();
        echo 'Migrated migrateBonuses';
    }

    public function actionBrokerEmails()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerEmails();
        echo 'Migrated migrateBrokerEmails';
    }

    public function actionBrokerLanguages()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerLanguages();
        echo 'Migrated migrateBrokerLanguages';
    }

    public function actionBrokerPositions()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerLanguagePositions();
        echo 'Migrated migrateBrokerLanguagePositions';
    }

    public function actionLotteryPositions()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateLotteryLanguagePositions();
        echo 'Migrated migrateLotteryLanguagePositions';
    }

    public function actionBrokerPaymentMethods()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerPaymentMethods();
        echo 'Migrated migrateBrokerPaymentMethods';
    }

    public function actionBrokerPhone()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerPhone();
        echo 'Migrated migrateBrokerPhone';
    }

    public function actionBrokerLottery()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateBrokerToLottery();
        echo 'Migrated migrateBrokerToLottery';
    }

    public function actionContactMessages()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateContactMessages();
        echo 'Migrated migrateContactMessages';
    }

    public function actionSubscribe()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateSubscribe();
        echo 'Migrated migrateSubscribe';
    }

    public function actionSitemapChanges()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateSitemapChanges();
        echo 'Migrated migrateSitemapChanges';
    }

    public function actionSitemapSettings()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateSitemapSettings();
        echo 'Migrated migrateSitemapSettings';
    }

    public function actionSlider()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateSlider();
        echo 'Migrated migrateSlider';
    }

    public function actionI18n()
    {
        $migrationModel = new MigrateOldData();
        $migrationModel->migrateI18n();
        echo 'Migrated migrateI18n';
    }
}