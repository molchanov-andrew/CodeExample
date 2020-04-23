<?php

namespace common\models\records;

use Yii;

/**
 * This is the model class for table "BrokerStatus".
 *
 * @property int $id
 * @property string $name
 * @property int $isPositive
 * @property int $mainPageImageId
 * @property int $listImageId
 * @property int $brokerPageImageId
 *
 * @property Broker[] $brokers
 * @property Image $mainPageImage
 * @property Image $listImage
 * @property Image $brokerPageImage
 */
class BrokerStatus extends \common\models\basic\ActiveRecord
{
    const MAIN_PAGE_IMAGE = 'mainPageImage';
    const LIST_IMAGE = 'listImage';
    const BROKER_PAGE_IMAGE = 'brokerPageImage';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'BrokerStatus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mainPageImageId', 'listImageId', 'brokerPageImageId'], 'required'],
            [['isPositive', 'mainPageImageId', 'listImageId', 'brokerPageImageId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['mainPageImageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['mainPageImageId' => 'id']],
            [['listImageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['listImageId' => 'id']],
            [['brokerPageImageId'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['brokerPageImageId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'isPositive' => 'Is Positive',
            'mainPageImageId' => 'Main Page Image ID',
            'listImageId' => 'List Image ID',
            'brokerPageImageId' => 'Broker Page Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers()
    {
        return $this->hasMany(Broker::class, ['statusId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainPageImage()
    {
        return $this->hasOne(Image::class, ['id' => 'mainPageImageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListImage()
    {
        return $this->hasOne(Image::class, ['id' => 'listImageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerPageImage()
    {
        return $this->hasOne(Image::class, ['id' => 'brokerPageImageId']);
    }

    public function beforeDelete()
    {
        if(!empty($this->brokers)){
            foreach ($this->brokers as $broker) {
                $this->unlink('brokers',$broker,false);
            }
        }
        return parent::beforeDelete();
    }

    public function isPositive()
    {
        return (bool)$this->isPositive;
    }
}
