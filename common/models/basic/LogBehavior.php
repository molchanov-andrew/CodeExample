<?php
namespace common\models\basic;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class LogBehavior extends Behavior
{
    const ACTION_CREATED = 'created';
    const ACTION_CHANGED = 'changed';
    const ACTION_REMOVED = 'removed';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'log',
            ActiveRecord::EVENT_AFTER_UPDATE => 'log',
            ActiveRecord::EVENT_AFTER_DELETE => 'log',
        ];
    }

    /**
     * @param $event
     * Creates log message in json format and writes to logs.
     */
    public function log($event)
    {
        $this->owner;
        $tableName = get_class($this->owner).'';
        $tmpParts = explode("\\",$tableName);
        $class = end($tmpParts);
        $message = ['class' => $class, 'data' => []];
        switch ($event->name)
        {
            case ActiveRecord::EVENT_AFTER_INSERT:{
                $message['action'] = self::ACTION_CREATED;
                break;
            }
            case ActiveRecord::EVENT_AFTER_UPDATE:{
                $message['action'] = self::ACTION_CHANGED;
                break;
            }
            case ActiveRecord::EVENT_AFTER_DELETE:{
                $message['action'] = self::ACTION_REMOVED;
                break;
            }
        }
        $attributes = $this->owner->getAttributes();
        $message['data']['attributes'] = $attributes;
        if(isset($event->changedAttributes))
        {
            $message['data']['changedAttributes'] = $event->changedAttributes;
        }
        $message = json_encode($message);
        Yii::info($message,'application\logBehavior');
    }
}