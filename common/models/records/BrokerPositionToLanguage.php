<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;

/**
 * This is the model class for table "BrokerPositionToLanguage".
 *
 * @property int $brokerId
 * @property int $languageId
 * @property int $position
 *
 * @property Broker $broker
 * @property Language $language
 */
class BrokerPositionToLanguage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BrokerPositionToLanguage';
    }

    /**
     * Firstly we batch delete all positions by language, then batch insert them.
     * @param $data
     * @param $languageId
     * @throws \yii\db\Exception
     */
    public static function updatePositions($data, $languageId)
    {

        \Yii::$app
            ->db
            ->createCommand()
            ->delete(self::tableName(), ['languageId' => $languageId])
            ->execute();

        \Yii::$app
            ->db
            ->createCommand()
            ->batchInsert(self::tableName(),['brokerId','languageId','position'], $data)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brokerId', 'languageId', 'position'], 'required'],
            [['brokerId', 'languageId', 'position'], 'integer'],
            [['brokerId', 'languageId'], 'unique', 'targetAttribute' => ['brokerId', 'languageId']],
            [['brokerId'], 'exist', 'skipOnError' => true, 'targetClass' => Broker::class, 'targetAttribute' => ['brokerId' => 'id']],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['languageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'brokerId' => 'Broker ID',
            'languageId' => 'Language ID',
            'position' => 'Position',
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
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'languageId']);
    }
}
