<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/31/18
 * Time: 12:25 PM
 */

namespace app\modules\rest\models;


class PViewAction extends PActiveAction
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        return $model;
    }
}