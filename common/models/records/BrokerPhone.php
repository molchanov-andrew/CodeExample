<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "BrokerPhone".
 *
 * @property int $id
 * @property int $brokerId
 * @property int $countryId
 * @property string $phone
 *
 * @property Broker $broker
 * @property Country $country
 */
class BrokerPhone extends \common\models\basic\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BrokerPhone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerId', 'countryId', 'phone'], 'required'],
            [['brokerId', 'countryId'], 'integer'],
            [['phone'], 'string', 'max' => 25],
            [['brokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['brokerId' => 'id']],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['countryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brokerId' => 'Broker ID',
            'countryId' => 'Country ID',
            'phone' => 'Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroker()
    {
        return $this->hasOne(Broker::class, ['id' => 'brokerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'countryId']);
    }
}
