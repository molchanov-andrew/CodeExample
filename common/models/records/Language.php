<?php

namespace common\models\records;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Language".
 *
 * @property int $id
 * @property string $iso
 * @property string $name
 * @property int $translatable
 * @property int $published
 * @property string $created
 * @property string $updated
 *
 * @property Image $image
 * @property BannerToPageContent[] $bannerToPageContents
 * @property BrokerPositionToLanguage[] $brokerPositionToLanguages
 * @property Broker[] $brokers
 * @property Broker[] $brokers0
 * @property Country[] $countries
 * @property LotteryPositionToLanguage[] $lotteryPositionToLanguages
 * @property Lottery[] $lotteries
 * @property PageContent[] $pageContents
 * @property Page[] $pages
 * @property Slider[] $sliders
 */
class Language extends \common\models\basic\ActiveRecord
{
    const IS_TRANSLATABLE = 1;
    const IS_NOT_TRANSLATABLE = 0;

    const FIRST_LANGUAGE_ID = 1;

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
        return 'Language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iso', 'name', 'imageId'], 'required'],
            [['published', 'translatable', 'imageId'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['iso'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 255],
            [['iso'], 'unique'],
            [['imageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['imageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso' => 'Iso',
            'name' => 'Name',
            'published' => 'Published',
            'translatable' => 'Translatable',
            'imageId' => 'Image ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerToPageContents()
    {
        return $this->hasMany(BannerToPageContent::class, ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerPositionToLanguages()
    {
        return $this->hasMany(BrokerPositionToLanguage::class, ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers()
    {
        return $this->hasMany(Broker::class, ['id' => 'brokerId'])->viaTable('BrokerPositionToLanguage', ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers0()
    {
        return $this->hasMany(Broker::class, ['id' => 'brokerId'])->viaTable('BrokerToLanguage', ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::class, ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteryPositionToLanguages()
    {
        return $this->hasMany(LotteryPositionToLanguage::class, ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteries()
    {
        return $this->hasMany(Lottery::class, ['id' => 'lotteryId'])->viaTable('LotteryPositionToLanguage', ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContents()
    {
        return $this->hasMany(PageContent::class, ['languageId' => 'id'])->inverseOf('language');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['id' => 'pageId'])->viaTable('PageContent', ['languageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSliders()
    {
        return $this->hasMany(Slider::class, ['languageId' => 'id']);
    }

    public function beforeDelete()
    {
        if(!empty($this->bannerToPageContents)){
            foreach ($this->bannerToPageContents as $item) {
                $item->delete();
            }
        }
        if(!empty($this->brokerPositionToLanguages)){
            foreach ($this->brokerPositionToLanguages as $item) {
                $item->delete();
            }
        }
        if(!empty($this->brokers0)){
            foreach ($this->brokers0 as $item) {
                $this->unlink('brokers0',$item,true);
            }
        }
        if(!empty($this->countries)){
            foreach ($this->countries as $item) {
                $item->delete();
            }
        }
        if(!empty($this->lotteryPositionToLanguages)){
            foreach ($this->lotteryPositionToLanguages as $item) {
                $item->delete();
            }
        }
        if(!empty($this->pageContents)){
            foreach ($this->pageContents as $item) {
                $item->delete();
            }
        }
        if(!empty($this->sliders)){
            foreach ($this->sliders as $item) {
                $item->delete();
            }
        }
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
    }
}
