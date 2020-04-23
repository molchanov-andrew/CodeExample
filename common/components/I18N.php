<?php

namespace common\components;

use common\models\records\Language;
use backend\modules\translations\Module;

class I18N extends \vintage\i18n\components\I18N
{
//    public $missingTranslationHandler = [Module::class, 'missingTranslation'];

    public function init()
    {
        $languages = Language::find()->andWhere(['translatable' => Language::IS_TRANSLATABLE])->all();
        $this->languages = array_column($languages,'iso');
        $this->missingTranslationHandler = [Module::class, 'missingTranslation'];
        parent::init();
    }

//    public function translate($category, $message, $params, $language)
//    {
//        return parent::translate($category, $message, $params, $language);
//    }
}