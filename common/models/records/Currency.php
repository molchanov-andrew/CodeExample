<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use common\models\queries\CurrencyQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Currency".
 *
 * @property int $id
 * @property string $iso
 * @property string $name
 * @property string $symbol
 * @property int $costOneDollar
 * @property int $published
 * @property string $created
 * @property string $updated
 *
 * @property Country[] $countries
 */
class Currency extends ActiveRecord
{
    const DEFAULT_CURRENCY_ID = 1;

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
        return 'Currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iso', 'name', 'symbol', 'costOneDollar', 'published'], 'required'],
            [['published'], 'integer'],
            [['costOneDollar'],'double'],
            [['created', 'updated'], 'safe'],
            [['iso'], 'string', 'max' => 3],
            [['name', 'symbol'], 'string', 'max' => 50],
            [['iso'], 'unique'],
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
            'symbol' => 'Symbol',
            'costOneDollar' => 'Cost One Dollar',
            'published' => 'Published',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    public static function find()
    {
        return new CurrencyQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::class, ['currencyId' => 'id']);
    }

    public function beforeDelete()
    {
        if(!empty($this->countries)){
            foreach ($this->countries as $country) {
                $this->unlink('countries',$country,false);
            }
        }
        return parent::beforeDelete();
    }
}
