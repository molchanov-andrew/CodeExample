<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 1/31/18
 * Time: 11:17 AM
 */

namespace app\modules\rest\models;


use yii\db\ActiveRecord;
use yii\rest\ActiveController;

class PActiveController extends ActiveController
{
    protected $_expands = [];
    public $searchModelClass = null;

    public function init()
    {
        parent::init();
        $this->_addExpandsDynamically();
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['class'] = 'app\modules\rest\models\PIndexAction';
        $actions['index']['expands'] = $this->_expands;
        $actions['index']['searchModelClass'] = $this->searchModelClass;
        $actions['view']['class'] = 'app\modules\rest\models\PViewAction';
        $actions['view']['expands'] = $this->_expands;
//        $actions['update']['class'] = 'app\modules\rest\models\PUpdateAction';
//        $actions['update']['expands'] = $this->_expands;
        return $actions;
    }

    protected function _addExpandsDynamically()
    {
        $expands = \Yii::$app->request->get('expand',null);
        if(empty($expands) || is_array($expands)){
            return false;
        }
        $model = new $this->modelClass;
        $expands = explode(',',$expands);
        foreach ($expands as $expand) {
            if($model->hasMethod('get'.ucfirst($expand))){
                $this->_expands[] = $expand;
            }
        }
        return true;
    }
}