<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/31/18
 * Time: 12:04 PM
 */

namespace app\modules\rest\models;

use app\modules\rest\models\PActiveActionHelper;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

class PActiveAction extends Action
{
    public $expands = [];
    public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $query = $modelClass::find()->andWhere(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $query = $modelClass::find()->andWhere([reset($keys) => $id]);
        }
        else{
            return false;
        }
        $query = PActiveActionHelper::appendExpands($query,$this->expands);
        $model = $query->one();

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException("Object not found: $id");
    }
}