<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Subscribe".
 *
 * @property int $id
 * @property string $languageIso
 * @property string $email
 * @property string $name
 * @property string $choosedLotteries
 * @property string $created
 */
class Subscribe extends ActiveRecord
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['timestampBehaviour'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created',
            'updatedAtAttribute' => null,
            'value' => new Expression('NOW()'),
        ];
        return $behaviours;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Subscribe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['languageIso', 'email', 'name', 'choosedLotteries'], 'required'],
            [['created'], 'safe'],
            [['languageIso'], 'string', 'max' => 2],
            [['email', 'name', 'choosedLotteries'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'languageIso' => 'Language Iso',
            'email' => 'Email',
            'name' => 'Name',
            'choosedLotteries' => 'Choosed Lotteries',
            'created' => 'Created',
        ];
    }
}
