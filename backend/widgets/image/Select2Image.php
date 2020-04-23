<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 2/2/18
 * Time: 1:57 PM
 */

namespace backend\widgets\image;

use common\models\records\Image;
use kartik\select2\Select2;
use Yii;
use yii\bootstrap\Html;
use yii\web\JsExpression;

class Select2Image extends Select2
{
    const SELECT2IMAGE_CLASS_NAME = 'select2-image-widget';

    public function init()
    {
        $this->addon = null;
        $this->imagesToArray();

        $this->_setSelectedValue();

        $this->options['class'] = (isset($this->options['class']) && is_string($this->options['class'])) ? $this->options['class'].' '.self::SELECT2IMAGE_CLASS_NAME : self::SELECT2IMAGE_CLASS_NAME;

        $this->options['placeholder'] = 'Select Image';

        if($this->theme === null) {
            $this->theme = Select2::THEME_BOOTSTRAP;
        }

        parent::init();

    }

    public function imagesToArray(){
        $array = [];
        $arrayAdditionalOptionsLogoImage = [];

        foreach ($this->data as $image) {
            $array[$image->id] = $image->fileName;
            $arrayAdditionalOptionsLogoImage[$image->id] = ['data-image-src' => Yii::$app->params['frontendUrl'] . $image->filePath];
        }
        $this->options['options'] = $arrayAdditionalOptionsLogoImage;
        $this->options['prompt'] = 'No image';
        $this->data = $array;
    }

    protected function _setSelectedValue()
    {
        $fieldName = $this->attribute;
        if(!empty($this->model->$fieldName) && $optionKey = array_search($this->model->$fieldName,$this->options['options'])){
            $this->options['options'][$optionKey]['selected'] = 'selected';
        }
    }

    public function renderWidget()
    {
        $result = new JsExpression('function(image){            
            var format = $(\'<span>\'+image.text+\'</span>\');
            var src = \'\';
            if(typeof image.element !== typeof undefined){            
                var src = image.element.getAttribute(\'data-image-src\');
            }
            if(typeof src !== typeof undefined && src !== null && src !== ""){
                var format = $(\'<span><img class="select2-image" src="\'+src+\'">\'+image.text+\'</span>\');        
            }
            return format;
        }');
        $this->pluginOptions['templateResult'] = $this->pluginOptions['templateSelection'] = $result;
        $this->renderWidgetParentPart();
    }

    public function renderWidgetParentPart(){
        $this->initI18N(__DIR__);
        $this->pluginOptions['theme'] = $this->theme;
        $this->options['multiple'] = false;
        if ($this->hideSearch) {
            $this->pluginOptions['minimumResultsForSearch'] = new JsExpression('Infinity');
        }
        Html::addCssClass($this->options, 'form-control');
        $this->initLanguage('language', true);
        $this->registerAssets();
        $this->renderInput();
    }
}