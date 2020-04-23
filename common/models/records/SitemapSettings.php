<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;

/**
 * This is the model class for table "SitemapSettings".
 *
 * @property int $id
 * @property string $area
 * @property string $areaParameter
 * @property string $changefreq
 * @property double $priority
 * @property string $lastmod
 */
class SitemapSettings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SitemapSettings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area'], 'required'],
            [['area', 'changefreq'], 'string'],
            [['priority'], 'number'],
            [['lastmod'], 'safe'],
            [['areaParameter'], 'string', 'max' => 255],
            [['area', 'areaParameter'], 'unique', 'targetAttribute' => ['area', 'areaParameter']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area' => 'Area',
            'areaParameter' => 'Area Parameter',
            'changefreq' => 'Changefreq',
            'priority' => 'Priority',
            'lastmod' => 'Lastmod',
        ];
    }
}
