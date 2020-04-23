<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "BrokerToLottery".
 *
 * @property int $id
 * @property int $brokerId
 * @property int $lotteryId
 * @property int $syndicat
 * @property double $price
 * @property string $url
 *
 * @property Broker $broker
 * @property Lottery $lottery
 * @property Systematic[] $systematics
 * @property Discount[] $discounts
 */
class BrokerToLottery extends \common\models\basic\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BrokerToLottery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerId', 'lotteryId', 'syndicat','price',], 'required'],
            [['brokerId', 'lotteryId', 'syndicat'], 'integer'],
            [['price'], 'number'],
            [['url'], 'string', 'max' => 255],
            [['brokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['brokerId' => 'id']],
            [['lotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lotteryId' => 'id']],
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
            'lotteryId' => 'Lottery ID',
            'syndicat' => 'Syndicat',
            'price' => 'Price',
            'url' => 'Url',
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
    public function getLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'lotteryId']);
    }

    public function getDiscounts()
    {
        return $this->hasMany(Discount::class, ['brokerToLotteryId' => 'id']);
    }

    public function getSystematics()
    {
        return $this->hasMany(Systematic::class, ['brokerToLotteryId' => 'id']);
    }

    public function beforeDelete()
    {
        if(!empty($this->systematics)){
            foreach ($this->systematics as $systematic) {
                $systematic->delete();
            }
        }
        if(!empty($this->discounts)){
            foreach ($this->discounts as $discounts) {
                $discounts->delete();
            }
        }
        return parent::beforeDelete();
    }
}
