<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "LotteryPositionToLanguage".
 *
 * @property int $lotteryId
 * @property int $languageId
 * @property int $position
 *
 * @property Lottery $lottery
 * @property Language $language
 */
class LotteryPositionToLanguage extends \common\models\basic\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LotteryPositionToLanguage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotteryId', 'languageId', 'position'], 'required'],
            [['lotteryId', 'languageId', 'position'], 'integer'],
            [['languageId', 'lotteryId'], 'unique', 'targetAttribute' => ['languageId', 'lotteryId']],
            [['lotteryId', 'languageId'], 'unique', 'targetAttribute' => ['lotteryId', 'languageId']],
            [['lotteryId'], 'exist', 'skipOnError' => true, 'targetClass' => Lottery::class, 'targetAttribute' => ['lotteryId' => 'id']],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['languageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lotteryId' => 'Lottery ID',
            'languageId' => 'Language ID',
            'position' => 'Position',
        ];
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
            ->batchInsert(self::tableName(),['lotteryId','languageId','position'], $data)
            ->execute();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLottery()
    {
        return $this->hasOne(Lottery::class, ['id' => 'lotteryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'languageId']);
    }
}
