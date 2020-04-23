<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;

/**
 * This is the model class for table "BannerToPageContent".
 *
 * @property int $bannerId
 * @property int $languageId
 * @property int $pageId
 *
 * @property Banner $banner
 * @property Language $language
 * @property Page $page
 */
class BannerToPageContent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BannerToPageContent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bannerId', 'languageId', 'pageId'], 'required'],
            [['bannerId', 'languageId', 'pageId'], 'integer'],
            [['bannerId', 'languageId', 'pageId'], 'unique', 'targetAttribute' => ['bannerId', 'languageId', 'pageId']],
            [['bannerId'], 'exist', 'skipOnError' => true, 'targetClass' => Banner::class, 'targetAttribute' => ['bannerId' => 'id']],
            [['languageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['languageId' => 'id']],
            [['pageId'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['pageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bannerId' => 'Banner ID',
            'languageId' => 'Language ID',
            'pageId' => 'Page ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanner()
    {
        return $this->hasOne(Banner::class, ['id' => 'bannerId']);
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
    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'pageId']);
    }
}
