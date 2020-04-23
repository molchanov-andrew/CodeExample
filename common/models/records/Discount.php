<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Discount".
 *
 * @property int $id
 * @property int $brokerToLotteryId
 * @property string $discount
 * @property string $description
 * @property string $created
 * @property string $updated
 *
 * @property BrokerToLottery $brokerToLottery
 */
class Discount extends ActiveRecord
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
        return 'Discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerToLotteryId'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['discount'], 'string', 'max' => 15],
            [['description'], 'string', 'max' => 100],
            [['brokerToLotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => BrokerToLottery::class, 'targetAttribute' => ['brokerToLotteryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brokerToLotteryId' => 'Broker To Lottery ID',
            'discount' => 'Discount',
            'description' => 'Description',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerToLottery()
    {
        return $this->hasOne(BrokerToLottery::class, ['id' => 'brokerToLotteryId']);
    }
}
