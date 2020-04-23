<?php
namespace backend\modules\translations\models\query;

use common\models\records\Message;
use common\models\records\SourceMessage;

class SourceMessageQuery extends \vintage\i18n\models\query\SourceMessageQuery
{
    public function notTranslatedByLanguage($language)
    {
        $notTranslated = Message::find()->select(['id'])->andWhere('language = :language and translation is not null and translation <> ""', [':language' => $language])->all();
        if(empty($notTranslated)){
            return $this->where('1=0');
        }
        return $this->andWhere(['not in', SourceMessage::tableName() . '.id',array_column($notTranslated,'id')]);
    }

    public function translatedByLanguage($language)
    {
        $notTranslated = Message::find()->select(['id'])->andWhere('language = :language and translation is not null and translation <> ""', [':language' => $language])->all();
        if(empty($notTranslated)){
            return $this->where('1=0');
        }

        return $this->andWhere(['in',SourceMessage::tableName() . '.id',array_column($notTranslated,'id')]);
    }
}