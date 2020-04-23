<?php
namespace backend\models;

use common\models\records\Banner;
use common\models\records\BannerToPageContent;
use common\models\records\Bonus;
use common\models\records\Broker;
use common\models\records\BrokerEmail;
use common\models\records\BrokerPhone;
use common\models\records\BrokerStatus;
use common\models\records\BrokerToLottery;
use common\models\records\ContactMessages;
use common\models\records\Country;
use common\models\records\Currency;
use common\models\records\Discount;
use common\models\records\Image;
use common\models\records\Language;
use common\models\records\Lottery;
use common\models\records\Message;
use common\models\records\Page;
use common\models\records\PageContent;
use common\models\records\PaymentMethod;
use common\models\records\SitemapChanges;
use common\models\records\SitemapSettings;
use common\models\records\Slider;
use common\models\records\SourceMessage;
use common\models\records\Subscribe;
use common\models\records\Systematic;
use yii\base\Model;
use yii\db\Connection;

/* @property Connection $db */
/* @property Connection $currentDb */
class MigrateOldData extends Model
{
    const MODULE_NAMES = [
        'AboutUs' =>'AboutUs',
        'Article' =>'Article',
        'BrokerPage' =>'BrokerPage',
        'BrokersTable' =>'BrokersTable',
        'BuyOnline' =>'BuyOnline',
        'BuyOnlineLotto' =>'BuyOnlineLotto',
        'CompareBrokers' =>'CompareBrokers',
        'ContactUs' =>'ContactUs',
        'LottoPage' =>'LottoPage',
        'LottoResultsPage' =>'LottoResultsPage',
        'LottoResultsPageByCountry' =>'LottoResultsPageByCountry',
        'LottoriesTable' =>'LottoriesTable',
        'LottoStatistics' =>'LottoStatistics',
        'MainPage' =>'MainPage',
        'NewsCatalog' =>'NewsCatalog',
        'NotFound' =>'NotFound',
        'Tools' =>'Tools',
        'ToolsHotNumbers' =>'ToolsHotNumbers',
        'ToolsRandomNumbers' =>'ToolsRandomNumbers'
    ];

    const NEW_TO_OLD_MODULES = [
        'broker' =>'BrokerPage',
        'lottery' =>'LottoPage',
        'buy-online-lottery' =>'BuyOnlineLotto',
        'article' =>'Article',
        'lottery-result' =>'LottoStatistics',
        'compare-brokers' =>'CompareBrokers',
        'home' =>'MainPage',
        'last-results-by-country-table' =>'LottoResultsPageByCountry',
        'last-results-table' =>'LottoResultsPage',
        'brokers-table' =>'BrokersTable',
        'lotteries-table' =>'LottoriesTable',
        'buy-online-table' =>'BuyOnline',
        'articles-list' =>'NewsCatalog',
        'tools-list' =>'Tools',
        'not-found' =>'NotFound',
        'tool-hot-numbers' =>'ToolsHotNumbers',
        'tool-random-numbers' =>'ToolsRandomNumbers',
        'contact-us' =>'ContactUs',
        'about-us' =>'AboutUs',
    ];

    const BASE_PATH_FOR_IMAGES = '/var/www/localotto.com';

    public $db;
    public $currentDb;
    public function init()
    {
        $this->db = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=localotto',
            'username' => 'root',
            'password' => '111',
            'charset' => 'utf8',
        ]);

        $this->currentDb = new Connection([
            'dsn' => 'mysql:host=localhost;dbname=new_localotto',
            'username' => 'root',
            'password' => '111',
            'charset' => 'utf8',
        ]);

        set_time_limit(0);
        parent::init();
    }

    public function migratePaymentMethods()
    {
        $query = <<<SQL
        SELECT * FROM `payment_methods`;
SQL;
        $pms = $this->db->createCommand($query)->queryAll();
        $paymentMethodImagePath = self::BASE_PATH_FOR_IMAGES . '/public/payment methods/';
        foreach ($pms as $pm) {
            $image = Image::createFromPath($paymentMethodImagePath . $pm['default'] . '.png',Image::CATEGORY_PAYMENT_METHODS);
            $newPmModel = new PaymentMethod(['id' => $pm['id'], 'name' => $pm['default'], 'imageId' => $image->id]);
            $newPmModel->save();
            unset($newPmModel);
        }
    }

    public function migrateLotteries()
    {
        $query = <<<SQL
        SELECT * FROM `lottories`;
SQL;
        $lotteries = $this->db->createCommand($query)->queryAll();
        foreach ($lotteries as $lottery) {
            $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . $lottery['image'],Image::CATEGORY_LOTTERIES);
            $newLottery = new Lottery([
                'id' => $lottery['lottery_id'],
                'published' => $lottery['show-this-lotto'],
                'name' => $lottery['lottery_name'],
                'jackpot' => (float)$lottery['jackpot'],
                'cost' => (float)$lottery['cost'],
                'systematic' => $lottery['systematic'],
                'mainNumbers' => $lottery['main_numbers'],
                'mainNumbersToCheck' => $lottery['main_numbers_to_check'],
                'mainNumbersDescription' => $lottery['main_numbers_description'],
                'addNumbers' => $lottery['add_numbers'],
                'addNumbersToCheck' => $lottery['add_numbers_to_check'],
                'addNumbersDescription' => $lottery['add_numbers_description'],
                'chanceToWin' => $lottery['chance_to_win'],
                'overallChance' => $lottery['overall_chance'],
                'numberAmounts' => '',
                'logoImageId' => ($image instanceof Image) ? $image->id : null,
                'countryId' => $lottery['country_id'],
                'parentLotteryId' => null,
            ]);
            $newLottery->save();
            $errors = $newLottery->getErrors();
            if(!empty($errors)){
                print_r($errors);
                return;
            }
            unset($newLottery,$errors);
        }
    }

    public function migrateBrokerStatuses()
    {
        $image = Image::createFromPath('/var/www/localotto.com/public/img/varifications-brokers/label-attention-cs.png',Image::CATEGORY_BROKER_STATUSES);
        $positive = ['tested','recommended','best-price'];
        foreach ($positive as $statusName) {
            $status = new BrokerStatus([
                'name' => $statusName,
                'isPositive' => 1,
                'mainPageImageId' => $image->id,
                'listImageId' => $image->id,
                'brokerPageImageId' => $image->id,
            ]);
            $status->save();
            unset($status);
        }
        $negative = ['pending', 'attention', 'scam', 'not_recommended'];
        foreach ($negative as $statusName) {
            $status = new BrokerStatus([
                'name' => $statusName,
                'isPositive' => 0,
                'mainPageImageId' => $image->id,
                'listImageId' => $image->id,
                'brokerPageImageId' => $image->id,
            ]);
            $status->save();
            unset($status);
        }
    }

    public function migrateBrokers()
    {
        $query = <<<SQL
        SELECT * FROM `brokers`;
SQL;
        $brokers = $this->db->createCommand($query)->queryAll();
        $statuses = BrokerStatus::find()->indexBy('name')->all();
        $statusTested = clone $statuses['tested'];
        unset($statuses['tested']);

        foreach ($brokers as $broker) {
            /** @var BrokerStatus $status */
            foreach ($statuses as $status) {
                // TODO:chek are statuses parsed good.
                if($broker[$status->name] == '1'){
                    $statusId = $status->id;
                } elseif($broker['tested'] == '1'){
                    $statusId = $statusTested->id;
                } else {
                    $statusId = $statuses['attention']->id;
                }
            }
            $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . '/public/brokers-logo/' . $broker['broker_name'] . '.png',Image::CATEGORY_BROKERS);
            $newBroker = new Broker([
                'id' => $broker['broker_id'],
                'published' => $broker['published'],
                'name' => $broker['broker_name'],
                'site' => $broker['broker_site'],
                'year' => $broker['year'],
                'clicks' => $broker['clicks'],
                'minimalDeposit' => $broker['min_deposit'],
                'disableIframe' => $broker['disable_iframe'],
                'syndicat' => $broker['syndicat'],
                'systematic' => $broker['systematic'],
                'scanTicket' => $broker['scan_ticket'],
                'chat' => $broker['chat'],
                'security' => $broker['security'],
                'support' => $broker['support'],
                'gameplay' => $broker['gameplay'],
                'promotions' => $broker['promotions'],
                'withdrawals' => $broker['withdrawals'],
                'usability' => $broker['usability'],
                'gameSelection' => $broker['game_selection'],
                'discounts' => $broker['discounts'],
                'marks' => $broker['marks'],
                'summaryMarks' => $broker['sum_marks'],
                'imageId' => ($image instanceof Image) ? $image->id : null,
                'statusId' => $statusId,
                // TODO: needs status id
            ]);
            $newBroker->save();
            $errors = $newBroker->getErrors();
            if(!empty($errors)){
                print_r($errors);
            }
            unset($newBroker,$statusId,$errors);
        }
    }

    public function migrateLanguages()
    {
        $translatableLanguages = [
            'en',
            'ru',
            'uk',
            'es',
            'fr',
            'it',
            'cs',
            'de',
            'ja'
        ];
        $query = <<<SQL
        SELECT * FROM `languages`;
SQL;
        $languageImagsPath = self::BASE_PATH_FOR_IMAGES . '/public/languages/';
        $languages = $this->db->createCommand($query)->queryAll();
        foreach ($languages as $language) {
            $image = Image::createFromPath( "{$languageImagsPath}{$language['iso']}.png",Image::CATEGORY_LANGUAGES);
            $imageId = null;
            if($image instanceof Image){
                $imageId = $image->id;
            }
            $newLanguage = new Language([
                'id' => $language['id'],
                'iso' => $language['iso'],
                'name' => $language['default'],
                'published' => $language['published'],
                'translatable' => \in_array($language['iso'], $translatableLanguages, true) ? 1 : 0,
                'imageId' => $imageId
            ]);
            $newLanguage->save();
            unset($language,$newLanguage);
        }
    }

    public function migratePages()
    {
        $query = <<<SQL
        SELECT * FROM `pages`,`urls` WHERE `pages`.page_id = `urls`.page_id ORDER BY `pages`.`language_id` ASC,`pages`.`page_id` ASC;
SQL;
        $pages = $this->db->createCommand($query)->queryAll();
        $sorted = self::_getPagesListSortedByModules($pages);
//        /uploaded_images/<?=$tool['img'];
        foreach ($sorted as $module => $array) {
            foreach ($array as $page) {
                $englishPage = $page['pages'][1];
                $title = $page['title'];
                $lotteryId = null;
                $brokerId = null;
                $countryId = null;
                if($module === 'BrokerPage'){
                    $brokerId = $englishPage['is_broker'];
                } elseif($module === 'BuyOnlineLotto' ){
                    $lotteryId = $englishPage['is_sell-lotto'];
                } elseif($module === 'LottoPage'){
                    $lotteryId = $englishPage['is_lottery'];
                } elseif($module === 'LottoResultsPageByCountry'){
                    $countryId = $englishPage['lotto_by_country_id'];
                }

                if($module === 'Article'){
                    try{
                        $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . '/uploaded_images/' . $englishPage['img'],Image::CATEGORY_PAGES);
                    } catch (\Exception $exception){
                        echo $exception->getMessage();
                        $image = null;
                    }
                }

                $newPage = new Page([
                    'name' => $title,
                    'module' => array_search($module,self::NEW_TO_OLD_MODULES,true),
                    'promotingBrokerId' => $englishPage['promoting_broker_id'] == '0' ? null : $englishPage['promoting_broker_id'],
                    'lotteryId' => $lotteryId,
                    'brokerId' => $brokerId,
                    'countryId' => $countryId,
                ]);
                $newPage->save();
                $pageErrors = $newPage->getErrors();
                if(!empty($pageErrors)){
                    print_r($pageErrors);
                    unset($pageErrors);
                    return;
                }
                foreach ($page['pages'] as $languageId => $pageContent) {
                    if($module === 'ToolsHotNumbers' ||
                        $module === 'ToolsRandomNumbers'){
                        try{
                            $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . '/uploaded_images/' . $pageContent['img'],Image::CATEGORY_PAGES);
                        } catch (\Exception $exception){
                            echo $exception->getMessage();
                            $image = null;
                        }
                    }
                    $imageId = null;
                    if(isset($image) && $image instanceof Image){
                        $imageId = $image->id;
                    }
                    $pageContent['url'] = strpos($pageContent['url'],'/') === 0 ? substr($pageContent['url'],1) : $pageContent['url'];
                    $newPageContent = new PageContent([
                        'url' => $pageContent['url'],
                        'title' => $pageContent['title'],
                        'keywords' => $pageContent['seo_keywords'],
                        'description' => $pageContent['seo_description'],
                        'additionalDescription' => $pageContent['description_for_home_page'],
                        'alternativeDescription' => $pageContent['alt_for_image'],
                        'content' => $pageContent['content'] . '[moduleData]' . $pageContent['content2'],
                        'published' => $pageContent['published'],
                        'imageId' => $imageId,
                        'languageId' => $pageContent['language_id'],
                        'pageId' => $newPage->id,
                    ]);
                    $newPageContent->save();
                    $errors = $newPageContent->getErrors();
                    if(!empty($errors)){
                        print_r($errors);
                        return;
                    }
                    unset($newPageContent,$errors);
                }
                unset($newPage, $image);
            }
        }
        echo 1;
    }

    /**
     * Creates array with structure like Module -> entity -> page -> language.
     * Ex: ['LottoriesTable' => Array (
     * [5] => Array
     * (
     * [title] => Online Lotteries – Online lottery reviews at LocaLotto.com,
     * [pages] => Array (
     * [en] => 5,
     * [ru] => 154,
     * [es] => 8,
     *                  ),
     * )
     *      )]
     * The logic of method makes english page`s id an identifier.
     * So if page don't have english languages - it will be out of this list.
     * @param array $params
     * @return array
     */

    private static function _getPagesListSortedByModules($pages,$params = array())
    {
        $modules = array();

        $tmpPages = $pages;
        $brokers = Broker::find()->select(['id','name'])->all();
        $lotteries = Lottery::find()->select(['id','name'])->all();
        $countries = Country::find()->select(['id','name'])->all();
        $data = [
            'brokers' => array_column($brokers,'name','id'),
            'lotteries' => array_column($lotteries,'name','id'),
            'countries' => array_column($countries,'name','id'),
        ];

        foreach ($tmpPages as $pageKey => $page) {
            if($page['language_id'] == 1) {
                $title = self::_getPageTitle($page,$data);
                // First check is here already module like page.
                if(!array_key_exists($page['module'],$modules)) {
                    $modules[$page['module']] = array();

                    $modules[$page['module']][$page['page_id']] = array(
                        'title' => $title,
                        'pages' => array(
                            $page['language_id'] => $page
                        )
                    );
                    continue;
                } else {
                    $modules[$page['module']][$page['page_id']] = array(
                        'title' => $title,
                        'pages' => array(),
                    );
                    $modules[$page['module']][$page['page_id']]['pages'][$page['language_id']] = $page;
                }
            }

            // Check is here module of same entity.
            switch ($page['module'])
            {
                case self::MODULE_NAMES['Article']:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('img','module'));
                    break;
                }
                case self::MODULE_NAMES['BrokerPage']:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('is_broker','module'));
                    break;
                }
                case self::MODULE_NAMES['BuyOnlineLotto']:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('is_sell-lotto','module'));
                    break;
                }
                case self::MODULE_NAMES['LottoPage']:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('is_lottery','module'));
                    break;
                }
                case self::MODULE_NAMES['LottoResultsPageByCountry']:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('lotto_by_country_id','module'));
                    break;
                }
                default:{
                    $pageFound = self::_checkRelationByParams($page,$modules[$page['module']],array('module'));
                    break;
                }
            }

            // If entity found - add this page.
            if($pageFound)
            {
                $modules[$page['module']][$pageFound]['pages'][$page['language_id']] = $page;
            }
            unset($tmpPages[$pageKey]);
        }

        if(isset($params['result']) && $params['result'] == 'ids')
        {
            foreach ($modules as $moduleKey => $entities) {
                foreach ($entities as $entityKey => $entity) {
                    foreach ($entity['pages'] as $pageKey => $page) {
                        $modules[$moduleKey][$entityKey]['pages'][$pageKey] = $page['page_id'];
                    }
                }
            }
        }

        return $modules;
    }

    private static function _getPageTitle($page,$data){
        switch ($page['module'])
        {
            case self::MODULE_NAMES['BrokerPage']:{
                if(array_key_exists($page['is_broker'], $data['brokers'])){
                    return $data['brokers'][$page['is_broker']] . 'broker review';
                }

                break;
            }
            case self::MODULE_NAMES['BuyOnlineLotto']:{
                if(array_key_exists($page['is_sell-lotto'], $data['lotteries'])){
                    return $data['lotteries'][$page['is_sell-lotto']] . 'lottery buy online';
                }

                break;
            }
            case self::MODULE_NAMES['LottoPage']:{
                if(array_key_exists($page['is_lottery'], $data['lotteries'])){
                    return $data['lotteries'][$page['is_lottery']] . 'lottery review';
                }

                break;
            }
            case self::MODULE_NAMES['LottoResultsPageByCountry']:{
                if(array_key_exists($page['lotto_by_country_id'], $data['countries'])){
                    return 'lotteries in country ' . $data['countries'][$page['lotto_by_country_id']];
                }

                break;
            }
            default:{
                return  !empty($page['title']) ? $page['title'] : 'Unknown title';
                break;
            }
        }
        return  !empty($page['title']) ? $page['title'] : 'Unknown title';
    }

    /**
     * Check is page related to any page of entity.
     * @param $page
     * @param $entities
     * @param array $params
     * @return bool|int|string
     */
    private static function _checkRelationByParams($page, $entities, $params = [])
    {
        foreach ($entities as $key => $entity) {
            if(!empty($entity['pages']))
            {
                $entityPage = reset($entity['pages']);
                $paramsAreOk = true;
                foreach ($params as $param) {
                    $paramsAreOk = ($page[$param] == $entityPage[$param]);
                    if(!$paramsAreOk)
                    {
                        break;
                    }
                }
                if($paramsAreOk){
                    return $key;
                }
            }
        }
        return false;
    }

    public function migrateCurrencies()
    {
        $query = <<<SQL
        SELECT * FROM `currencies`;
SQL;
        $currencies = $this->db->createCommand($query)->queryAll();
        foreach ($currencies as $currency) {
            $currency['default'] = ($currency['default'] === 'Dollar') ? 'USD' : $currency['default'];
            $newCurrency = new Currency([
                'id' => $currency['id'],
                'iso' => $currency['default'],
                'name' => $currency['default'],
                'symbol' => $currency['symbol'],
                'costOneDollar' => (double)$currency['cost_one_dollar'],
                'published' => $currency['publish'],
            ]);
            $newCurrency->save();
            $errors = $newCurrency->getErrors();
            if(!empty($errors)){
                print_r($errors);
                return;
            }
            unset($newCurrency);
        }
    }

    public function migrateCountries()
    {
        $query = <<<SQL
        SELECT * FROM `countries`;
SQL;
        $countries = $this->db->createCommand($query)->queryAll();
        foreach ($countries as $country) {
            $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . '/public/countries/' . $country['default'] . '.png',Image::CATEGORY_COUNTRIES);
            $country['iso'] = empty($country['iso']) ? 'NO' : $country['iso'];
            $newCountry = new Country([
                'id' => $country['id'],
                'name' => $country['default'],
                'iso' => $country['iso'],
                'currencyId' => $country['currency_id'],
                'languageId' => $country['language_id'],
                'imageId' => ($image instanceof Image) ? $image->id : null,
            ]);
            $newCountry->save();
            $errors = $newCountry->getErrors();
            $this->_printError($newCountry);
            unset($country,$newCountry,$errors);
        }
    }

    public function migrateBanners()
    {
        $pageContents = PageContent::find()->select(['pageId','languageId','url'])->all();
        $query = <<<SQL
        SELECT * FROM `banner`;
SQL;
        $banners = $this->db->createCommand($query)->queryAll();
        $images = [];
        foreach ($banners as $banner) {
            $banner['url'] = strpos($banner['url'],'/') === 0 ? substr($banner['url'],1) : $banner['url'];
            $foundPageContent = array_filter($pageContents,function($value)use($banner){
                return $value->url === $banner['url'];
            });
            if(!empty($foundPageContent)){
                $foundPageContent = reset($foundPageContent);
                if (!empty($banner['link_right_banner']) && !empty($banner['href_right_banner'])) {
                    $path = self::BASE_PATH_FOR_IMAGES . $banner['link_right_banner'];
                    if(array_key_exists($path,$images)){
                        $image = $images[$path];
                    } else {
                        $image = Image::createFromPath($path,Image::CATEGORY_BANNERS);
                        $images[$path] = $image;
                    }
                    if($image instanceof Image){
                        $this->_makeBannerByData(['imageId' => $image->id, 'href' => $banner['href_right_banner'], 'position' => Banner::POSITION_RIGHT_TOP, 'pageId' => $foundPageContent->pageId,'languageId' => $foundPageContent->languageId]);
                    }
                }
                if (!empty($banner['link_bottom_banner']) && !empty($banner['href_bottom_banner'])) {
                    $path = self::BASE_PATH_FOR_IMAGES . $banner['link_bottom_banner'];
                    if(array_key_exists($path,$images)){
                        $image = $images[$path];
                    } else {
                        $image = Image::createFromPath($path,Image::CATEGORY_BANNERS);
                        $images[$path] = $image;
                    }
                    if($image instanceof Image){
                        $this->_makeBannerByData(['imageId' => $image->id, 'href' => $banner['href_bottom_banner'], 'position' => Banner::POSITION_BOTTOM, 'pageId' => $foundPageContent->pageId,'languageId' => $foundPageContent->languageId]);
                    }
                }
                if (!empty($banner['link_right_banner2']) && !empty($banner['href_right_banner2'])) {
                    $path = self::BASE_PATH_FOR_IMAGES . $banner['link_right_banner2'];
                    if(array_key_exists($path,$images)){
                        $image = $images[$path];
                    } else {
                        $image = Image::createFromPath($path,Image::CATEGORY_BANNERS);
                        $images[$path] = $image;
                    }
                    if($image instanceof Image){
                        $this->_makeBannerByData(['imageId' => $image->id, 'href' => $banner['href_right_banner2'], 'position' => Banner::POSITION_RIGHT_TOP, 'pageId' => $foundPageContent->pageId,'languageId' => $foundPageContent->languageId]);
                    }
                }
            }
        }
    }

    private function _makeBannerByData($banner){

        $model = new Banner([
            'link' => $banner['href'],
            'position' => $banner['position'],
            'imageId' => $banner['imageId'],
        ]);
        $model->save();
        $errors = $model->getErrors();
        $this->_printError($model);
        $pageRelation = new BannerToPageContent([
            'bannerId' => $model->id,
            'languageId' => $banner['languageId'],
            'pageId' => $banner['pageId'],
        ]);
        $pageRelation->save();
        $this->_printError($pageRelation);
        unset($model,$errors);
    }

    private function _printError($model){
        $errors = $model->getErrors();
        if(!empty($errors)){
            print_r($errors);
            exit;
        }
    }

    public function migrateBonuses()
    {
        $query = <<<SQL
        SELECT * FROM `bonuses`;
SQL;
        $bonuses = $this->db->createCommand($query)->queryAll();
        $query2 = <<<SQL
        SELECT * FROM `broker-bonuses`;
SQL;
        $bonusRelations = $this->db->createCommand($query2)->queryAll();
        foreach ($bonuses as $bonus) {
            $newBonus = new Bonus(['name' => $bonus['default']]);
            $newBonus->save();
            $errors = $newBonus->getErrors();
            $this->_printError($newBonus);
            $currentRelations = array_filter($bonusRelations,function($value)use($bonus){
                return $value['bonuses_id'] === $bonus['id'];
            });
            foreach ($currentRelations as $currentRelation) {
                $broker = Broker::find()->andWhere(['id' => $currentRelation['broker_id']])->one();
                if($broker !== null){
                    $broker->link('bonuses',$newBonus);
                }
            }

            unset($bonus,$newBonus,$errors);
        }
    }

    public function migrateBrokerEmails()
    {
        $query = <<<SQL
        SELECT * FROM `broker-emails`;
SQL;
        $emails = $this->db->createCommand($query)->queryAll();
        foreach ($emails as $email) {
            $name = empty($email['email']) ? 'None' : $email['email'];
            $brokerEmail = new BrokerEmail([
                'brokerId' => $email['broker_id'],
                'translatable' => $email['translatable'],
                'name' => $name,
            ]);
            $brokerEmail->save();
            $errors = $brokerEmail->getErrors();
            $this->_printError($brokerEmail);
            unset($email,$brokerEmail,$errors);
        }
    }

    public function migrateBrokerLanguages()
    {
        $query = <<<SQL
        SELECT * FROM `broker-languages`;
SQL;
        $relations = $this->db->createCommand($query)->queryAll();
        $brokers = Broker::find()->indexBy('id')->all();
        $languages = Language::find()->indexBy('id')->all();
        foreach ($relations as $relation) {
            if(isset($brokers[$relation['broker_id']],$languages[$relation['language_id']])){
                $brokers[$relation['broker_id']]->link('languages',$languages[$relation['language_id']]);
            }
            unset($relation);
        }
    }

    public function migrateBrokerLanguagePositions()
    {
        $query = <<<SQL
        SELECT * FROM `brokers-positions-per-languages`;
SQL;
        $relations = $this->db->createCommand($query)->queryAll();
        $brokers = Broker::find()->indexBy('id')->all();
        $languages = Language::find()->indexBy('id')->all();
        foreach ($relations as $relation) {
            if(isset($brokers[$relation['broker_id']],$languages[$relation['language_id']])){
                $brokers[$relation['broker_id']]->link('languagesByBrokerPosition',$languages[$relation['language_id']],['position' => $relation['position']]);
            }
            unset($relation);
        }
    }

    public function migrateLotteryLanguagePositions()
    {
        $query = <<<SQL
        SELECT * FROM `lotteries-positions-per-languages`;
SQL;
        $relations = $this->db->createCommand($query)->queryAll();
        $lotteries = Lottery::find()->indexBy('id')->all();
        $languages = Language::find()->indexBy('id')->all();
        foreach ($relations as $relation) {
            if(isset($lotteries[$relation['lottery_id']],$languages[$relation['language_id']])){
                $lotteries[$relation['lottery_id']]->link('languages',$languages[$relation['language_id']],['position' => $relation['position']]);
            }
            unset($relation);
        }
    }

    public function migrateBrokerPaymentMethods()
    {
        $query = <<<SQL
        SELECT * FROM `broker-paymentMethods`;
SQL;
        $relations = $this->db->createCommand($query)->queryAll();
        $brokers = Broker::find()->indexBy('id')->all();
        $paymentMethods = PaymentMethod::find()->indexBy('id')->all();
        foreach ($relations as $relation) {
            if(isset($brokers[$relation['broker_id']],$paymentMethods[$relation['paymentMethod_id']])){
                $brokers[$relation['broker_id']]->link('paymentMethods',$paymentMethods[$relation['paymentMethod_id']]);
            }
            unset($relation);
        }
    }

    public function migrateBrokerPhone()
    {
        $brokers = Broker::find()->indexBy('id')->all();
        $query = <<<SQL
        SELECT * FROM `broker-phones`;
SQL;
        $phones = $this->db->createCommand($query)->queryAll();
        foreach ($phones as $phone) {
            if(!empty($phone['phone']) && array_key_exists($phone['broker_id'],$brokers)){
                $brokerPhone = new BrokerPhone([
                    'brokerId' => $phone['broker_id'],
                    'countryId' => $phone['country_id'],
                    'phone' => $phone['phone'],
                ]);
                $brokerPhone->save();
                $this->_printError($brokerPhone);
                unset($phone,$brokerPhone,$errors);
            }
        }
    }

    public function migrateBrokerToLottery()
    {
        $brokers = Broker::find()->indexBy('id')->all();
        $lotteries = Lottery::find()->indexBy('id')->all();
        $query = <<<SQL
        SELECT * FROM `broker-lottories`;
SQL;
        $btls = $this->db->createCommand($query)->queryAll();

        $query = <<<SQL
        SELECT * FROM `broker-lottery-discounts`;
SQL;
        $discounts = $this->db->createCommand($query)->queryAll();

        $query = <<<SQL
        SELECT * FROM `broker-lottery-systematics`;
SQL;
        $systematics = $this->db->createCommand($query)->queryAll();

        foreach ($btls as $btl) {
            if(isset($brokers[$btl['broker_id']],$lotteries[$btl['lottery_id']])) {
                $brokerToLottery = new BrokerToLottery([
                    'brokerId' => $btl['broker_id'],
                    'lotteryId' => $btl['lottery_id'],
                    'syndicat' => $btl['syndicat'],
                    'price' => (float)$btl['price'],
                    'url' => $btl['url'],
                ]);
                $brokerToLottery->save();
                $this->_printError($brokerToLottery);

                $systematicsForRelation = array_filter($systematics,function($value)use($btl){
                   return $value['broker_lottery_id'] === $btl['id'];
                });
                if(!empty($systematicsForRelation)){
                    foreach ($systematicsForRelation as $systematic){
                        $this->_createSystematic($systematic,$brokerToLottery->id);
                    }
                }

                $discountsForRelation = array_filter($discounts,function($value)use($btl){
                    return $value['broker_lottery_id'] === $btl['id'];
                });
                if(!empty($discountsForRelation)){
                    foreach ($discountsForRelation as $discount){
                        $this->_createDiscount($discount,$brokerToLottery->id);
                    }
                }

                unset($btl,$brokerToLottery);
            }
        }

    }

    private function _createSystematic($array,$brokerLotteryId)
    {
        $newSystematic = new Systematic([
            'brokerToLotteryId' => $brokerLotteryId,
            'numbers' => $array['numbers'],
            'lines' => $array['lines'],
        ]);
        $newSystematic->save();
        $this->_printError($newSystematic);
    }

    private function _createDiscount($discount, $id)
    {
        $newDiscount = new Discount([
            'brokerToLotteryId' => $id,
            'discount' => $discount['discount'],
            'description' => $discount['default'],
        ]);
        $newDiscount->save();
        $this->_printError($newDiscount);
    }

    public function migrateContactMessages()
    {
        $query = <<<SQL
        SELECT * FROM `contacts_list`;
SQL;
        $contactMessages = $this->db->createCommand($query)->queryAll();
        foreach ($contactMessages as $contactMessage) {
            $model = new ContactMessages([
                'siteName' => $contactMessage['site_name'],
                'languageIso' => $contactMessage['language'],
                'fullName' => $contactMessage['full_name'],
                'email' => $contactMessage['email'],
                'phone' => $contactMessage['phone'],
                'message' => $contactMessage['message'],
                'created' => $contactMessage['time_arrived'],
                'isRead' => $contactMessage['is_readed'],
            ]);
            $model->save();
            $this->_printError($model);
            unset($contactMessage,$model,$errors);
        }
    }

    public function migrateSubscribe()
    {
        $query = <<<SQL
        SELECT * FROM `newsletter`;
SQL;
        $subscribes = $this->db->createCommand($query)->queryAll();
        foreach ($subscribes as $subscribe) {
            $model = new Subscribe([
                'languageIso' => $subscribe['language'],
                'email' => $subscribe['email'],
                'name' => $subscribe['name'],
                'choosedLotteries' => $subscribe['choosed_lottories'],
            ]);
            $model->save();
            $this->_printError($model);
            unset($subscribe,$model,$errors);
        }
    }

    public function migrateSitemapChanges()
    {
        $query = <<<SQL
        SELECT * FROM `sitemap_changes`;
SQL;
        $sitemapChanges = $this->db->createCommand($query)->queryAll();
        foreach ($sitemapChanges as $sitemapChange) {
            $model = new SitemapChanges([
                'type' => $sitemapChange['type'],
                'identifier' => $sitemapChange['identifier'],
                'lastmod' => $sitemapChange['lastmod'],
            ]);
            $model->save();
            $this->_printError($model);
            unset($sitemapChange,$model,$errors);
        }
    }

    public function migrateSitemapSettings()
    {
        $query = <<<SQL
        SELECT * FROM `sitemap_settings`;
SQL;
        $sitemapSettings = $this->db->createCommand($query)->queryAll();
        foreach ($sitemapSettings as $sitemapSetting) {
            $model = new SitemapSettings([
                'id' => $sitemapSetting['id'],
                'area' => $sitemapSetting['area'],
                'areaParameter' => $sitemapSetting['area_parameter'],
                'changefreq' => $sitemapSetting['changefreq'],
                'priority' => $sitemapSetting['priority'],
                'lastmod' => $sitemapSetting['lastmod'],
            ]);
            $model->save();
            $this->_printError($model);
            unset($sitemapSetting,$model,$errors);
        }
    }

    public function migrateSlider()
    {
        $languages = Language::find()->indexBy('iso')->all();
        $query = <<<SQL
        SELECT * FROM `slider`;
SQL;
        $sliders = $this->db->createCommand($query)->queryAll();
        foreach ($sliders as $slider) {
            $languageId = $languages[$slider['lang']]->id;
            $image = Image::createFromPath(self::BASE_PATH_FOR_IMAGES . $slider['src'],Image::CATEGORY_SLIDERS);
            if($image instanceof Image){
                $model = new Slider([
                    'languageId' => $languageId,
                    'imageId' => $image->id,
                    'link' => $slider['link'],
                    'alt' => $slider['alt'],
                    'position' => $slider['position'],
                    'name' => $slider['alt'],
                ]);
                $model->save();
                $this->_printError($model);
            }
            unset($slider,$model,$errors);
        }
    }

    public function migrateI18n()
    {
        $currentSourceMessages = SourceMessage::find()->one();
        if($currentSourceMessages !== null){
            return;
        }
        $query = <<<SQL
        SELECT * FROM `i18n`;
SQL;
        $translations = $this->db->createCommand($query)->queryAll();

        $brokers = Broker::find()->select(['name','site'])->asArray()->all();
        $data[SourceMessage::CATEGORY_LOTTERIES] = Lottery::find()->select(['name','mainNumbersDescription','addNumbersDescription'])->asArray()->all();
        foreach ($brokers as $broker) {
            $data[SourceMessage::CATEGORY_BROKERS][] = ['name' => $broker['name']];
            $data[SourceMessage::CATEGORY_BROKER_LINKS][] = ['site' => $broker['site']];
        }
        $data[SourceMessage::CATEGORY_BROKER_STATUSES] = BrokerStatus::find()->select(['name'])->asArray()->all();
        $data[SourceMessage::CATEGORY_BROKER_EMAILS] = BrokerEmail::find()->select(['name'])->andWhere(['translatable' => 1])->asArray()->all();
        $data[SourceMessage::CATEGORY_BROKER_TO_LOTTERY_LINK] = BrokerToLottery::find()->select(['url'])->asArray()->all();
        $data[SourceMessage::CATEGORY_LANGUAGES] = Language::find()->select(['name'])->asArray()->all();
        $data[SourceMessage::CATEGORY_COUNTRIES] = Country::find()->select(['name'])->asArray()->all();
        $data[SourceMessage::CATEGORY_CURRENCIES] = Currency::find()->select(['name'])->asArray()->all();
        $data[SourceMessage::CATEGORY_PAYMENT_METHODS] = PaymentMethod::find()->select(['name'])->asArray()->all();
        $data[SourceMessage::CATEGORY_BONUSES] = Bonus::find()->select(['name'])->asArray()->all();

        foreach ($translations as $translation) {
            $result = $this->_getI18nCategory($translation['default'],$data);
            $value = $result['value'];
            $category = $result['category'];
            if($value === null){
                $convertToEnIfSame = $translation['default'] === strtolower($translation['en']) ? $translation['en'] : $translation['default'];
            } else {
                $convertToEnIfSame = $value;
            }
            $sourceMessage = new SourceMessage([
                'message' => $convertToEnIfSame,
                'category' => $category
            ]);
            if($sourceMessage->save()){
                foreach ($translation as $key => $item) {
                    if( $key !== 'i18n_id' &&
                        $key !== 'link' &&
                        $key !== 'default' &&
                        $item !== '' ) {
                        $message = new Message([
                            'id' => $sourceMessage->id,
                            'language' => $key,
                            'translation' => $item
                        ]);
                        $message->save();
                    }
                }
            }
        }
    }

    private function _getI18nCategory($translation,$data)
    {
        foreach ($data as $category => $dataOfCategory) {
            foreach ($dataOfCategory as $item) {
                foreach ($item as $fieldName => $value) {
                    if($translation == strtolower($value)){
                        return ['value' => $value, 'category' => $category];
                    }
                }
            }
        }
        return ['value' => null, 'category' => SourceMessage::CATEGORY_GENERAL];
    }
}