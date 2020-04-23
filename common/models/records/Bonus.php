<?php

namespace common\models\records;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Bonus".
 *
 * @property int $id
 * @property string $name
 * @property string $created
 * @property string $updated
 *
 * @property Broker[] $brokers
 */
class Bonus extends \common\models\basic\ActiveRecord
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
        return 'Bonus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers()
    {
        return $this->hasMany(Broker::class, ['id' => 'brokerId'])->viaTable('BrokerToBonus', ['bonusId' => 'id']);
    }

    public function beforeDelete()
    {
        if(!empty($this->brokers)){
            foreach ($this->brokers as $broker) {
                $this->unlink('brokers',$broker,true);
            }
        }
        return parent::beforeDelete();
    }
}
