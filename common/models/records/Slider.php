<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Slider".
 *
 * @property int $id
 * @property int $languageId
 * @property int $imageId
 * @property string $link
 * @property string $alt
 * @property int $position
 * @property string $name
 * @property string $created
 * @property string $updated
 *
 * @property Language $language
 * @property Image $image
 */
class Slider extends ActiveRecord
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
        return 'Slider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['languageId', 'imageId', 'name'], 'required'],
            [['languageId', 'imageId', 'position'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['link', 'alt', 'name'], 'string', 'max' => 255],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['languageId' => 'id']],
            [['imageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['imageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'languageId' => 'Language ID',
            'imageId' => 'Image ID',
            'link' => 'Link',
            'alt' => 'Alt',
            'position' => 'Position',
            'name' => 'Name',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'languageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
    }
}
