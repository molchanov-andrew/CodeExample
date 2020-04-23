<?php
namespace common\models\basic;

use backend\models\response\AjaxResponse;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['logBehavior'] = LogBehavior::class;
        return $behaviours;
    }

    public static function changeMultiple($data)
    {
        if(!isset($data['rows']) || empty($data['rows'])){
            return new AjaxResponse(['status' => 'error', 'message' => 'No chosen items.']);
        }
        $rows = explode(',',$data['rows']);
        $summary = count($rows);
        $changedCount = 0;
        $models = static::find()->andWhere(['id' => $rows])->all();
        foreach ($models as $model) {
            $model->load($data);
            if($model->save()){
                $changedCount++;
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
        if(!is_array($rows)){
            $rows = explode(',',$data['rows']);
        }
        $changedCount = 0;
        $models = static::find()->andWhere(['id' => $rows])->all();

        foreach ($models as $model) {
            if($model->delete() !== false){
                $changedCount++;
            }
        }
        return new AjaxResponse(['message' => "Items deleted: {$changedCount}"]);
    }
}