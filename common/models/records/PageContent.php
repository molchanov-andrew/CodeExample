<?php

namespace common\models\records;

use backend\models\response\AjaxResponse;
use common\models\basic\ActiveRecord;
use common\models\queries\PageContentQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "PageContent".
 *
 * @property string $url
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $additionalDescription
 * @property string $alternativeDescription
 * @property string $content
 * @property int $published
 * @property string $created
 * @property string $updated
 * @property int $imageId
 * @property int $languageId
 * @property int $pageId
 *
 *
 * @property Banner $rightTopBanner
 * @property Banner $rightBottomBanner
 * @property Banner $bottomBanner
 * @property string $content1
 * @property string $content2
 * @property string $fullUrl
 * @property string $languageIso
 *
 * Relations
 * @property Page $page
 * @property Language $language
 * @property Image $image
 * @property BannerToPageContent[] $bannerToPageContents
 * @property Banner[] $banners
 */
class PageContent extends ActiveRecord
{
    const CONTENT_DIVIDER = '[moduleData]';

    private $_content1;
    private $_content2;

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
        return 'PageContent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['languageId', 'pageId'], 'required'],
            [['description', 'additionalDescription', 'alternativeDescription', 'content'], 'string'],
            [['published', 'imageId', 'languageId', 'pageId'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['url', 'title', 'keywords'], 'string', 'max' => 300],
            [['url'], 'unique'],
            [['languageId', 'pageId'], 'unique', 'targetAttribute' => ['languageId', 'pageId']],
            [['pageId'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['pageId' => 'id']],
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
            'url' => 'Url',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'additionalDescription' => 'Additional Description',
            'alternativeDescription' => 'Alternative Description',
            'content' => 'Content',
            'published' => 'Published',
            'created' => 'Created',
            'updated' => 'Updated',
            'imageId' => 'Image ID',
            'languageId' => 'Language ID',
            'pageId' => 'Page ID',
        ];
    }

    public static function find()
    {
        return new PageContentQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::class, ['id' => 'pageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'languageId'])->inverseOf('pageContents');
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
        return $this->hasMany(BannerToPageContent::class, ['pageId' => 'pageId', 'languageId' => 'languageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['id' => 'bannerId'])->viaTable('BannerToPageContent', ['pageId' => 'pageId', 'languageId' => 'languageId'])->indexBy('position');
    }

    public function beforeDelete()
    {
        if(!empty($this->bannerToPageContents)){
            foreach ($this->bannerToPageContents as $bannerToPageContent) {
                $bannerToPageContent->delete();
            }
        }
        return parent::beforeDelete();
    }

    public static function changeMultiple($data)
    {
        if(!isset($data['rows']) || empty($data['rows'])){
            return new AjaxResponse(['status' => 'error', 'message' => 'No chosen items.']);
        }
        $rows = $data['rows'];
        $summary = count($rows);
        $changedCount = 0;
        foreach ($rows as $row) {
            $model = static::find()->andWhere(['languageId' => $row['languageId'], 'pageId' => $row['pageId']])->one();
            if($model !== null){
                $model->load($data);
                if($model->save()){
                    $changedCount++;
                }
            }
        }
        return new AjaxResponse(['message' => "Items changed: {$changedCount} of {$summary}"]);
    }

    public static function deleteMultiple($data)
    {
        if(!isset($data['rows']) || empty($data['rows'])){
            return new AjaxResponse(['status' => 'error', 'message' => 'No chosen items.']);
        }
        $rows = $data['rows'];
        $changedCount = 0;
        foreach ($rows as $row) {
            $model = static::find()->andWhere(['languageId' => $row['languageId'], 'pageId' => $row['pageId']])->one();
            if($model !== null){
                $model->load($data);
                if($model->delete() !== false){
                    $changedCount++;
                }
            }
        }
        return new AjaxResponse(['message' => "Items deleted: {$changedCount}"]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if(!$insert){
            $this->_saveBannersRelations();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function linkBanner($bannerId)
    {
        $banner = Banner::findOne($bannerId);
        if($banner === null){
            throw new \RuntimeException('Banner not exists.');
        }
        if(array_key_exists($banner->position,$this->banners)){
            $this->unlinkBanner($this->banners[$banner->position]->id);
        }

        $this->link('banners',$banner);
        return $this;
    }

    public function unlinkBanner($bannerId)
    {
        $this->unlink('banners',Banner::findOne($bannerId),true);
        return $this;
    }

    public function _saveBannersRelations()
    {
        $pageContentData = Yii::$app->request->post('PageContent',null);
        if($pageContentData !== null && array_key_exists('banners',$pageContentData)){
            $banners = $pageContentData['banners'];
            $banners = array_filter($banners,function($value){
               return !empty($value);
            });

            foreach (Banner::getPositionList(true) as $position) {
                if(isset($this->banners[$position]) && !isset($banners[$position])){
                    $this->unlinkBanner($this->banners[$position]->id);
                } elseif ( (!isset($this->banners[$position]) && isset($banners[$position])) ||
                            (isset($this->banners[$position],$banners[$position]) && $this->banners[$position]->id !== $banners[$position]) ){
                    $this->linkBanner($banners[$position]);
                }
            }
        }
    }

    public function getLanguageIso()
    {
        return $this->language->iso;
    }

    public function getFullUrl()
    {
        return '\\' . $this->url;
    }

    /**
     * @return mixed
     */
    public function getContent1()
    {
        if($this->_content1 === null){
            $this->_divideContent();
        }
        return $this->_content1;
    }

    public function setContent1($content1)
    {
        $this->_content1 = $content1;
    }

    /**
     * @return mixed
     */
    public function getContent2()
    {
        if($this->_content2 === null){
            $this->_divideContent();
        }
        return $this->_content2;
    }

    public function setContent2($content2)
    {
        $this->_content2 = $content2;
    }

    protected function _divideContent(): bool
    {
        if(strpos($this->content,static::CONTENT_DIVIDER) !== false){
            list($this->_content1,$this->_content2) = explode(static::CONTENT_DIVIDER,$this->content);
            return true;
        }
        list($this->_content1,$this->_content2) = [str_replace(static::CONTENT_DIVIDER,'',$this->content),false];

        return false;
    }

    public function getRightTopBanner()
    {
        return $this->banners[Banner::POSITION_RIGHT_TOP] ?? null;
    }

    public function getRightBottomBanner()
    {
        return $this->banners[Banner::POSITION_RIGHT_BOTTOM] ?? null;
    }

    public function getBottomBanner()
    {
        return $this->banners[Banner::POSITION_BOTTOM] ?? null;
    }
}
