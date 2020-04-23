<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "Message".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $id0
 */
class Message extends \vintage\i18n\models\Message
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Message';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(SourceMessage::class, ['id' => 'id']);
    }
}
