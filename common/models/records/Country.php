<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Country".
 *
 * @property int $id
 * @property string $name
 * @property string $iso
 * @property string $created
 * @property string $updated
 * @property int $currencyId
 * @property int $languageId
 * @property int $imageId
 *
 * @property Image $image
 * @property BrokerPhone[] $brokerPhones
 * @property Page[] $pages
 * @property Currency $currency
 * @property Language $language
 */
class Country extends ActiveRecord
{
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
        return 'Country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'iso', 'currencyId', 'languageId'], 'required'],
            [['created', 'updated'], 'safe'],
            [['currencyId', 'languageId', 'imageId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['iso'], 'string', 'max' => 10],
            [['currencyId'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currencyId' => 'id']],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['languageId' => 'id']],
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
            'name' => 'Name',
            'iso' => 'Iso',
            'created' => 'Created',
            'updated' => 'Updated',
            'currencyId' => 'Currency ID',
            'languageId' => 'Language ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerPhones()
    {
        return $this->hasMany(BrokerPhone::class, ['countryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['countryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currencyId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'languageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
    }

    public function beforeDelete()
    {
        if(!empty($this->brokerPhones)){
            foreach ($this->brokerPhones as $brokerPhone) {
                $brokerPhone->delete();
            }
        }
        return parent::beforeDelete();
    }
}
