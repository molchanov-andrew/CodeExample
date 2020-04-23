<?php
namespace backend\modules\translations\models;

class DbMessageSource extends \yii\i18n\DbMessageSource
{
    protected function loadMessagesFromDb($category, $language)
    {
        $loadedMessages = parent::loadMessagesFromDb($category, $language);
        foreach ($loadedMessages as $message => &$translation) {
            if($translation === null){
                $translation = false;
            }
        }
        return $loadedMessages;
    }
}