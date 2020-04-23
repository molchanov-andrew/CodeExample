<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "Banner".
 *
 * @property int $id
 * @property string $link
 * @property string $position
 * @property int $imageId
 * @property string $created
 * @property string $updated
 *
 * @property string $positionName
 * @property Image $image
 * @property BannerToPageContent[] $bannerToPageContents
 */
class Banner extends ActiveRecord
{
    const POSITION_RIGHT_TOP = 'right_top';
    const POSITION_RIGHT_BOTTOM = 'right_bottom';
    const POSITION_BOTTOM = 'bottom';

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
        return 'Banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'position', 'imageId'], 'required'],
            [['imageId'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['link', 'position'], 'string', 'max' => 255],
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
            'link' => 'Link',
            'position' => 'Position',
            'imageId' => 'Image ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerToPageContents()
    {
        return $this->hasMany(BannerToPageContent::class, ['bannerId' => 'id']);
    }

    public static function getPositionList($keys = false)
    {
        return $keys ? [
            self::POSITION_RIGHT_TOP,
            self::POSITION_RIGHT_BOTTOM,
            self::POSITION_BOTTOM,
        ] : [
            self::POSITION_RIGHT_TOP => 'Right(top)',
            self::POSITION_RIGHT_BOTTOM => 'Right(bottom)',
            self::POSITION_BOTTOM => 'Bottom',
        ];
    }

    public function getPositionName()
    {
        $positionList = self::getPositionList();
        return isset($positionList[$this->position]) ? $positionList[$this->position] : 'Unknown';
    }

    public function beforeDelete()
    {
        $this->image->delete();
        if(!empty($this->bannerToPageContents)){
            foreach ($this->bannerToPageContents as $bannerToPageContent) {
                $bannerToPageContent->delete();
            }
        }
        return parent::beforeDelete();
    }

    public static function getBannersSortedByPosition()
    {
        $result = [];
        foreach (self::getPositionList(true) as $position) {
            $result[$position] = self::find()->with('image')->andWhere(['position' => $position])->all();
        }
        return $result;
    }
}
