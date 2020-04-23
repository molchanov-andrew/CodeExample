<?php

namespace common\models\records;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "ContactMessages".
 *
 * @property int $id
 * @property string $siteName
 * @property string $languageIso
 * @property string $fullName
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property string $created
 * @property int $isRead
 */
class ContactMessages extends \common\models\basic\ActiveRecord
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
        return 'ContactMessages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['created'], 'safe'],
            [['isRead'], 'integer'],
            [['siteName', 'fullName', 'email'], 'string', 'max' => 255],
            [['languageIso'], 'string', 'max' => 2],
            [['phone'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'siteName' => 'Site Name',
            'languageIso' => 'Language Iso',
            'fullName' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'created' => 'Created',
            'isRead' => 'Is Read',
        ];
    }
}
