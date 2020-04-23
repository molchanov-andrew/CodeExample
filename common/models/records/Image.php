<?php

namespace common\models\records;

use backend\models\response\AjaxResponse;
use common\models\queries\ImageQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

use yii\imagine\Image as ImagineImage;

/**
 * This is the model class for table "Image".
 *
 * @property int $id
 * @property string $fileName
 * @property string $fileHash
 * @property string $category
 * @property string $created
 * @property string $modified
 *
 * @property string $categoryName
 * @property Banner[] $banners
 * @property Broker[] $brokers
 * @property BrokerStatus[] $brokerStatuses
 * @property BrokerStatus[] $brokerStatuses0
 * @property BrokerStatus[] $brokerStatuses1
 * @property Lottery[] $lotteries
 * @property PageContent[] $pageContents
 * @property PaymentMethod[] $paymentMethods
 * @property Slider[] $sliders
 * @property string $filePath
 */
class Image extends \common\models\basic\ActiveRecord
{
    public $file;

    const CATEGORY_BANNERS = 'banners';
    const CATEGORY_BROKERS = 'brokers';
    const CATEGORY_BROKER_STATUSES = 'broker_statuses';
    const CATEGORY_LOTTERIES = 'lotteries';
    const CATEGORY_PAGES = 'pages';
    const CATEGORY_SLIDERS = 'sliders';
    const CATEGORY_PAYMENT_METHODS = 'payment_methods';
    const CATEGORY_OTHERS = 'others';
    const CATEGORY_LANGUAGES = 'languages';
    const CATEGORY_COUNTRIES = 'countries';

    const MIME_FORMATS = [
        'png' => 'image/png',
        'jpeg' => 'image/jpeg',
//        'jpe' => 'image/jpeg',
//        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
    ];

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['timestampBehaviour'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created',
            'updatedAtAttribute' => 'modified',
            'value' => new Expression('NOW()'),
        ];
        return $behaviours;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileName', 'fileHash',], 'required'],
            [['created', 'modified'], 'safe'],
            [['fileName'], 'string', 'max' => 128],
            [['fileHash'], 'string', 'max' => 32],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'File Name',
            'fileHash' => 'File Hash',
            'category' => 'Category',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokers()
    {
        return $this->hasMany(Broker::class, ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerStatuses()
    {
        return $this->hasMany(BrokerStatus::class, ['mainPageImageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerStatuses0()
    {
        return $this->hasMany(BrokerStatus::class, ['listImageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrokerStatuses1()
    {
        return $this->hasMany(BrokerStatus::class, ['brokerPageImageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLotteries()
    {
        return $this->hasMany(Lottery::class, ['logoImageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageContents()
    {
        return $this->hasMany(PageContent::class, ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(Language::class, ['imageId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSliders()
    {
        return $this->hasMany(Slider::class, ['imageId' => 'id']);
    }

    protected function getFileName(){
        $fileExtension = pathinfo($this->fileName, PATHINFO_EXTENSION);
        return $this->id . '_' . $this->fileHash . '.' . $fileExtension;
    }

    public function setFileName($filename)
    {
        $this->fileName = $filename;
    }

    public function getFilePath(){
        $path = null;
        $mediaPath = \Yii::$app->imageManager->mediaPath;
        $filePath = $mediaPath . DIRECTORY_SEPARATOR . $this->category . DIRECTORY_SEPARATOR . $this->getFileName();

        //check file exists

        if (file_exists(Yii::getAlias('@frontend') . '/web' . $filePath)) {
            $path = $filePath;

        }
        return $path;
    }

    /**
     * @return \Imagine\Image\ImageInterface|bool
     * @throws \yii\base\Exception
     */
    public function saveFile()
    {
        if($this->file === null){
            $this->file = UploadedFile::getInstance($this, 'file');
        }
        $mediaPath = \Yii::$app->imageManager->mediaPath;
        $this->fileHash = Yii::$app->getSecurity()->generateRandomString(32);
        $this->fileName = str_replace('_', '-', $this->fileName);
        $fileFormat = array_search($this->file->type, self::MIME_FORMATS, true);
        if(!$fileFormat) {
            throw new \Exception('Unknown image file type.');
        }
        if ($this->save()) {
            $saveFileName = $this->getFileName();
            $fullPath = Yii::getAlias('@frontend') . '/web/' . $mediaPath . DIRECTORY_SEPARATOR  . $this->category . DIRECTORY_SEPARATOR . $saveFileName;
            if(ImagineImage::getImagine()->open($this->file->tempName)->save($fullPath)){
                return $this;
            }
        }
        return false;
    }

    public static function createFromPath($path,$category = '')
    {

        $file = new \stdClass();
//        $imageFile = ImagineImage::getImagine()->open($path);
        try{
            $file->tempName = $path;
            $file->type = self::MIME_FORMATS[substr($path,strrpos($path,'.',-1)+1)];

        } catch (\Exception $e){
            return $e->getMessage();
        }
        $image = new self(['filename' => basename($path), 'category' => $category]);
        $image->file = $file;
        return $image->saveFile();
    }

    public static function getCategoryList()
    {
        $list = [
            self::CATEGORY_BANNERS,
            self::CATEGORY_BROKERS,
            self::CATEGORY_BROKER_STATUSES,
            self::CATEGORY_LOTTERIES,
            self::CATEGORY_PAGES,
            self::CATEGORY_SLIDERS,
            self::CATEGORY_PAYMENT_METHODS,
            self::CATEGORY_LANGUAGES,
            self::CATEGORY_COUNTRIES,
            self::CATEGORY_OTHERS,
        ];
        return array_combine($list,$list);
    }

    public function getCategoryName()
    {
        $list = self::getCategoryList();
        return isset($list[$this->category]) ? $list[$this->category] : 'Unknown';
    }

    public static function find()
    {
        return new ImageQuery(static::class);
    }

    public function beforeDelete()
    {
        if(!empty($this->banners)){
            foreach ($this->banners as $item) {
                $this->unlink('banners',$item,false);
            }
        }
        if(!empty($this->brokers)){
            foreach ($this->brokers as $item) {
                $this->unlink('brokers',$item,false);
            }
        }
        if(!empty($this->brokerStatuses)){
            foreach ($this->brokerStatuses as $item) {
                $this->unlink('brokerStatuses',$item,false);
            }
        }
        if(!empty($this->brokerStatuses0)){
            foreach ($this->brokerStatuses0 as $item) {
                $this->unlink('brokerStatuses0',$item,false);
            }
        }
        if(!empty($this->brokerStatuses1)){
            foreach ($this->brokerStatuses1 as $item) {
                $this->unlink('brokerStatuses1',$item,false);
            }
        }
        if(!empty($this->lotteries)){
            foreach ($this->lotteries as $item) {
                $this->unlink('lotteries',$item,false);
            }
        }
        if(!empty($this->pageContents)){
            foreach ($this->pageContents as $item) {
                $this->unlink('pageContents',$item,false);
            }
        }
        if(!empty($this->paymentMethods)){
            foreach ($this->paymentMethods as $item) {
                $this->unlink('paymentMethods',$item,false);
            }
        }
        if(!empty($this->sliders)){
            foreach ($this->sliders as $item) {
                $this->unlink('sliders',$item,false);
            }
        }
        return parent::beforeDelete();
    }

    public static function changeMultiple($data)
    {
        if(!isset($data['rows']) || empty($data['rows'])){
            return new AjaxResponse(['status' => 'error', 'message' => 'No chosen items.']);
        }
        $rows = explode(',',$data['rows']);
        $summary = count($rows);
        $changedCount = 0;
        /** @var Image $models */
        $models = static::find()->andWhere(['id' => $rows])->all();
        foreach ($models as $model) {
            $model->load($data);
            if(UploadedFile::getInstance($model, 'file') === null){
                if($model->save()){
                    $changedCount++;
                }
            } else {
                if($model->saveFile()){
                    $changedCount++;
                }
            }

        }
        return new AjaxResponse(['message' => "Items changed: {$changedCount} of {$summary}"]);
    }
}
