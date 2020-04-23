<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Setting".
 *
 * @property string $name
 * @property string $type
 * @property string $value
 * @property string $created
 * @property string $updated
 */
class Setting extends ActiveRecord
{
    const DEFAULT_BROKER_ID = 'defaultBrokerId';
    const AMOUNT_OF_BROKERS_BUY_ONLINE_LOTTERY = 'amountOfBrokersBuyOnlineLottery';
    const COUNT_OF_BROKERS_ON_BROKER_TABLE = 'countOfBrokersOnBrokerTable';
    const COUNT_OF_BROKER_ON_HOME_PAGE = 'countOfBrokersOnHomePage';
    const COUNT_OF_LOTTERIES_ON_HOME_PAGE = 'countOfLotteriesOnHomePage';
    const COUNT_OF_RESULTS_ON_HOME_PAGE = 'countOfResultsOnHomePage';
    const COUNT_OF_BROKERS_ON_LOTTERY_PAGE = 'countOfBrokersLotteryPage';

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
        return 'Setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['value'], 'string'],
            [['created', 'updated'], 'safe'],
            [['name', 'type'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'value' => 'Value',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
