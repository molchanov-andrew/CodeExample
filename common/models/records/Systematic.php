<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Systematic".
 *
 * @property int $id
 * @property int $brokerToLotteryId
 * @property int $numbers
 * @property int $lines
 * @property string $created
 * @property string $updated
 *
 * @property BrokerToLottery $brokerToLottery
 */
class Systematic extends ActiveRecord
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
        return 'Systematic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerToLotteryId', 'numbers', 'lines'], 'integer'],
            [['created', 'updated'], 'safe'],
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
            'numbers' => 'Numbers',
            'lines' => 'Lines',
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
