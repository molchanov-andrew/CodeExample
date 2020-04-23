<?php
namespace backend\models\validators;

use common\models\basic\ActiveRecord;
use common\models\records\Lottery;
use common\models\records\Page;
use InvalidArgumentException;
use yii\validators\Validator;
/*
 * Validator not allow to create more than one page per module but lottery broker reviews buy onlines and articles.
 * */
class PageUniqueValidator extends Validator
{
    public function validateAttribute($model,$attribute)
    {
        if(!($model instanceof Page)){
            throw new InvalidArgumentException('$model must be instance of page.');
        }

        switch ($model->$attribute) {
            case Page::MODULE_ARTICLE: {
                break;
            }
            case Page::MODULE_BROKER: {
                if($this->_checkPageAlreadyExists($model,['module' => $model->module, 'brokerId' => $model->brokerId])){
                    $this->addError($model,$attribute,'Page with module {module} and broker {broker} already exists',['module' => $model->module, 'broker' => $model->brokerId]);
                }
                break;
            }
            case Page::MODULE_LOTTERY:{
                if($this->_checkPageAlreadyExists($model,['module' => $model->module, 'lotteryId' => $model->lotteryId])){
                    $this->addError($model,$attribute,'Page with module {module} and lottery {lottery} already exists',['module' => $model->module, 'lottery' => $model->lotteryId]);
                }
                break;
            }
            case Page::MODULE_BUY_LOTTERY:{
                if(empty($model->lotteryId)){
                    break;
                }
                $lottery = Lottery::find()->andWhere(['id' => $model->lotteryId])->one();
                if(!($lottery instanceof Lottery) || (int)$lottery->brokerToLotteriesCount === 0){
                    $this->addError($model,$attribute,'This page can not be created as there is no agent selling this game');
                }
                if($this->_checkPageAlreadyExists($model,['module' => $model->module, 'lotteryId' => $model->lotteryId])){
                    $this->addError($model,$attribute,'Page with module {module} and lottery {lottery} already exists',['module' => $model->module, 'lottery' => $model->lotteryId]);
                }
                break;
            }
            case Page::MODULE_RESULTS_BY_COUNTRY:{
                if($this->_checkPageAlreadyExists($model,['module' => $model->module, 'countryId' => $model->countryId])){
                    $this->addError($model,$attribute,'Page with module {module} and country {country} already exists',['module' => $model->module, 'country' => $model->countryId]);
                }
                break;
            }
            default:{
                if($this->_checkPageAlreadyExists($model,['module' => $model->module])){
                    $this->addError($model,$attribute,'Page with module {module} already exists',['module' => $model->module]);
                }
                break;
            }
        }
    }

    private function _checkPageAlreadyExists(Page $model,$params)
    {
        $query = Page::find()->andWhere($params);
        if(!$model->isNewRecord){
            $query->andWhere(['not in','id',$model->id]);
        }
        return $query->one();
    }
}