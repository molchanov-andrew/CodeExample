<?php
namespace backend\modules\translations;

use common\models\records\SourceMessage;
use Yii;
use yii\i18n\MissingTranslationEvent;

class Module extends \yii\base\Module
{
    /**
     * @var int
     */
    public $pageSize = 50;

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $i18n = Yii::$app->getI18n();
        if (isset($i18n->excludedCategories)) {
            $excludeCategories = $i18n->excludedCategories;
        } else {
            $excludeCategories = [];
        }

        if (!in_array($event->category, $excludeCategories)) {
            /** @var SourceMessage $sourceMessage */
            $sourceMessage = SourceMessage::find()
                ->where('category = :category and message = :message', [
                    ':category' => $event->category,
                    ':message' => $event->message
                ])
                ->with('messages')
                ->one();

            if (!$sourceMessage) {
                $sourceMessage = new SourceMessage;
                $sourceMessage->setAttributes([
                    'category' => $event->category,
                    'message' => $event->message
                ], false);
                $sourceMessage->save(false);
            } elseif($sourceMessage->message !== $event->message && strtolower($sourceMessage->message) === strtolower($event->message)){
                $sourceMessage->message = $event->message;
                $sourceMessage->save();
            }
            $sourceMessage->initMessages();
            $sourceMessage->saveMessages();
            $event->translatedMessage = isset($sourceMessage->messages[$event->language]) ? $sourceMessage->messages[$event->language]->translation : null;
        }
    }
}