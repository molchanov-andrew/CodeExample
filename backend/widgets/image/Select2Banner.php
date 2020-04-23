<?php

namespace backend\widgets\image;


use common\models\records\Banner;
use Yii;

class Select2Banner extends Select2Image
{
    const SELECT2BANNER_CLASS_NAME = 'select2-banner-widget';
    public function init()
    {
        $this->options['class'] = (isset($this->options['class']) && is_string($this->options['class'])) ? $this->options['class'].' '.self::SELECT2BANNER_CLASS_NAME : self::SELECT2BANNER_CLASS_NAME;
        parent::init();
    }

    public function imagesToArray(){
        $array = [];
        $arrayAdditionalOptionsLogoImage = [];
        foreach ($this->data as $banner) {
            /** @var Banner $banner */
            $array[$banner->id] = $banner->link;
            $bannerImagePath = $banner->image === null ? null : $banner->image->filePath;
            $arrayAdditionalOptionsLogoImage[$banner->id] = ['data-image-src' => Yii::$app->params['frontendUrl'] . $bannerImagePath];
        }
        $this->options['options'] = $arrayAdditionalOptionsLogoImage;
        $this->options['prompt'] = 'No image';
        $this->data = $array;
    }
}