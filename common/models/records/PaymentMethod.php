<?php

namespace common\models\records;

use common\models\basic\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "PaymentMethod".
 *
 * @property int $id
 * @property string $name
 * @property int $imageId
 * @property string $created
 * @property string $updated
 *
 * @property Broker[] $brokers
 * @property Image $image
 */
class PaymentMethod extends ActiveRecord
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
        return 'PaymentMethod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'imageId'], 'required'],
            [['imageId'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'imageId' => 'Image ID',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers()
    {
        return $this->hasMany(Broker::class, ['id' => 'brokerId'])->viaTable('BrokerToPaymentMethod', ['paymentMethodId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'imageId']);
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
}
