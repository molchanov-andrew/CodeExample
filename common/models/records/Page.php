<?php

namespace common\models\records;

use backend\models\validators\PageUniqueValidator;
use common\models\basic\ActiveRecord;
use common\models\queries\PageContentQuery;
use common\models\queries\PageQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Page".
 *  In a case you need to make other logic of module validation - edit PageUniqueValidator.
 *
 * @property int $id
 * @property string $name Name of page, used only for understanding what is this page in admin panel.
 * @property string $module Identifies what module to use for rendering, depending to it we use different entities as foreignId.
 * @property int $promotingBrokerId
 * @property string $created
 * @property string $updated
 * @property int $brokerId
 * @property int $lotteryId
 * @property int $countryId
 *
 * @property string $moduleName
 *
 * @property Broker $broker
 * @property Lottery $lottery
 * @property Country $country
 * @property BannerToPageContent[] $bannerToPageContents
 * @property Broker $promotingBroker
 * @property PageContent[] $pageContents
 * @property Language[] $languages
 * @property PageContent $pageContentByLanguage need to be got by with() function coz this needs languageId of pageContent. Otherwise it gets english content.
 */
class Page extends ActiveRecord
{
    const MODULES = [
        self::MODULE_BROKER => 'Broker Review',
        self::MODULE_LOTTERY => 'Lottery Review',
        self::MODULE_BUY_LOTTERY => 'Buy Online Lottery',
        self::MODULE_ARTICLE => 'Article',
        self::MODULE_LOTTERY_RESULT => 'Lottery result',
        self::MODULE_COMPARE_BROKERS => 'Compare Brokers',
        self::MODULE_HOME => 'Homepage',
        self::MODULE_RESULTS_BY_COUNTRY => 'Last results by country table',
        self::MODULE_LAST_RESULTS_TABLE => 'Last results table',
        self::MODULE_BROKERS_TABLE => 'Brokers table',
        self::MODULE_LOTTERIES_TABLE => 'Lotteries table',
        self::MODULE_BUY_ONLINE_TABLE => 'Buy online table',
        self::MODULE_ARTICLES_LIST => 'Articles list',
        self::MODULE_TOOLS_LIST => 'Tools list',
        self::MODULE_NOT_FOUND => 'Not found',
        self::MODULE_TOOLS_HOT_NUMBERS => 'Tool hot numbers',
        self::MODULE_TOOLS_RANDOM_NUMBERS => 'Tool random numbers',
        self::MODULE_CONTACT_US => 'Contact Us',
        self::MODULE_ABOUT_US => 'About Us',
    ];
    const MODULE_BROKER = 'broker';
    const MODULE_LOTTERY = 'lottery';
    const MODULE_BUY_LOTTERY = 'buy-online-lottery';
    const MODULE_ARTICLE = 'article';
    const MODULE_RESULTS_BY_COUNTRY = 'last-results-by-country-table';
    const MODULE_LOTTERIES_TABLE = 'lotteries-table';
    const MODULE_BROKERS_TABLE = 'brokers-table';
    const MODULE_LAST_RESULTS_TABLE = 'last-results-table';
    const MODULE_BUY_ONLINE_TABLE = 'buy-online-table';
    const MODULE_TOOLS_LIST = 'tools-list';
    const MODULE_ARTICLES_LIST = 'articles-list';
    const MODULE_COMPARE_BROKERS = 'compare-brokers';
    const MODULE_HOME = 'home';
    const MODULE_CONTACT_US = 'contact-us';
    const MODULE_ABOUT_US = 'about-us';
    const MODULE_TOOLS_HOT_NUMBERS = 'tool-hot-numbers';
    const MODULE_TOOLS_RANDOM_NUMBERS = 'tool-random-numbers';
    const MODULE_LOTTERY_RESULT = 'lottery-result';
    const MODULE_NOT_FOUND = 'not-found';

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['timestampBehaviour'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created',
            'updatedAtAttribute' => 'updated',
            'value' => new Expression('NOW()'),
        ];
        return $behaviours;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotingBrokerId', 'brokerId', 'lotteryId','countryId',], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name', 'module'], 'string', 'max' => 255],
            [['promotingBrokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['promotingBrokerId' => 'id']],
            [['brokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['brokerId' => 'id']],
            [['lotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lotteryId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
//            [['module','lotteryId','brokerId','countryId'], 'unique', 'targetAttribute' => ['module','lotteryId','brokerId','countryId']],
            ['module',PageUniqueValidator::class]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'module' => 'Module',
            'promotingBrokerId' => 'Promoting Broker ID',
            'created' => 'Created',
            'updated' => 'Updated',
            'brokerId' => 'Broker ID',
            'lotteryId' => 'Lottery ID',
            'countryId' => 'Country ID',
        ];
    }

    public static function find()
    {
        return new PageQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerToPageContents()
    {
        return $this->hasMany(BannerToPageContent::class, ['pageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotingBroker()
    {
        return $this->hasOne(Broker::class, ['id' => 'promotingBrokerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContents()
    {
        return $this->hasMany(PageContent::class, ['pageId' => 'id'])->indexBy('languageId');
    }

    public function getPageContentByLanguage()
    {
        return $this->hasOne(PageContent::class, ['pageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['id' => 'languageId'])->viaTable('PageContent', ['pageId' => 'id']);
    }

    public function getLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'lotteryId']);
    }

    public function getBroker()
    {
        return $this->hasOne(Broker::class, ['id' => 'brokerId']);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }

    public function getModuleName()
    {
        $modulesList = self::MODULES;
        return array_key_exists($this->module,$modulesList) ? $modulesList[$this->module] : 'Unknown';
    }

    /**
     * @param Language[]|null $languages
     * @param array $exclude
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNotUsedLanguages($languages = null, array $exclude = []): array
    {
        if($languages === null){
            $languages = Language::find()->all();
        }
        if(empty($this->pageContents)){
            return $languages;
        }
        $usedLanguages = array_column($this->pageContents,'languageId');
        // When we need to save some languages even they are used.
        if(!empty($exclude)){
            $usedLanguages = array_filter($usedLanguages,function(int $value) use ($exclude) {
                return !\in_array($value, $exclude, true);
            });
        }
        return array_filter($languages,function(Language $value) use($usedLanguages) {
            return !\in_array($value->id, $usedLanguages, true);
        });
    }

    public function beforeDelete()
    {
        if(!empty($this->pageContents)){
            foreach ($this->pageContents as $pageContent) {
                $pageContent->delete();
            }
        }
        return parent::beforeDelete();
    }

    public static function findMenuPages()
    {
        return static::find()->menuModules()->indexBy('module');
    }
}
