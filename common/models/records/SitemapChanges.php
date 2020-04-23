<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;

/**
 * This is the model class for table "SitemapChanges".
 *
 * @property string $type
 * @property string $identifier
 * @property string $lastmod
 */
class SitemapChanges extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SitemapChanges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'identifier'], 'required'],
            [['type'], 'string'],
            [['lastmod'], 'safe'],
            [['identifier'], 'string', 'max' => 255],
            [['type', 'identifier'], 'unique', 'targetAttribute' => ['type', 'identifier']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'identifier' => 'Identifier',
            'lastmod' => 'Lastmod',
        ];
    }
}
