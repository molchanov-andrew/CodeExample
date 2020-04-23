<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/31/18
 * Time: 12:28 PM
 */

namespace app\modules\rest\models;

use yii\rest\IndexAction;

class PIndexAction extends IndexAction
{
    public $expands = [];
    public $searchModelClass = null;

    public function init()
    {
        if(!is_null($this->searchModelClass))
        {
            $this->prepareDataProvider = function(){
                $searchModel = new $this->searchModelClass();
                return $searchModel->search(array_merge(\Yii::$app->request->queryParams,['expands' => $this->expands]));
            };
        }
        parent::init();
    }
}