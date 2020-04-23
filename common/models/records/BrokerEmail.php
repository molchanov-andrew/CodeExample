<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;

/**
 * This is the model class for table "BrokerEmail".
 *
 * @property int $id
 * @property string $name
 * @property int $translatable
 * @property int $brokerId
 * @property string $translatedName
 *
 * @property Broker $broker
 */
class BrokerEmail extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BrokerEmail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'translatable', 'brokerId'], 'required'],
            [['translatable', 'brokerId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['brokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['brokerId' => 'id']],
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
            'translatable' => 'Translatable',
            'brokerId' => 'Broker ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroker()
    {
        return $this->hasOne(Broker::class, ['id' => 'brokerId']);
    }

    public function getTranslatedName()
    {
        return $this->translatable ? Yii::t(SourceMessage::CATEGORY_BROKER_EMAILS,$this->name) : $this->name;
    }
}
