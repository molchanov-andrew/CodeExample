<?php
namespace backend\widgets\translations;

use common\models\records\SourceMessage;
use yii\helpers\Html;

class Translate extends \yii\jui\Widget
{
    public $message;
    public $category;

    public function run()
    {
        if($this->message !== '' && $this->message !== null){
            $sourceMessage = SourceMessage::find()->andWhere(['message' => $this->message, 'category' => $this->category])->one();
            if($sourceMessage === null){
                $sourceMessage = new SourceMessage(['message' => $this->message, 'category' => $this->category]);
                $sourceMessage->save();
            }
            return Html::a('<i class="glyphicon glyphicon-globe"></i>',
                ['/translations/' . $sourceMessage->id],
                [
                    'class' => 'open-modal-link',
                    'data-toggle'=>'modal',
                    'data-target'=>'#modalGeneral',
                    'data-pjax' => '0',
                ]);
        }
        return Html::a('<i class="glyphicon glyphicon-globe color-red"></i>',
            'javascript:;');

    }
}