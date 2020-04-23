<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 3/29/18
 * Time: 4:12 PM
 */

namespace backend\modules\rest\controllers;


use backend\modules\rest\managers\LotteryManager;
use backend\modules\rest\managers\ManagerInterface;
use backend\modules\rest\managers\PageManager;
use DateTime;
use DateTimeZone;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class IndexController extends Controller
{
    private $_supportableEntities = ['page' => PageManager::class, 'lotteries' => LotteryManager::class];
    private $_salt = 'my$awsome%salt';
    private $_requestParams;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $body = Yii::$app->request->getRawBody();
        if($result = Json::decode($body)){
            $this->_requestParams = $result;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!isset($this->_requestParams['token']) || $this->_requestParams['token'] != $this->_getToken()) {
            exit;
        } else {
            unset($this->_requestParams['token']);
        }
        if(!isset($this->_requestParams['thirdEntity'])) {
            return ['status' => 'error', 'message' => 'Third entities not specified'];
        }

        if(array_key_exists($this->_requestParams['thirdEntity'],$this->_supportableEntities)) {
            $model = new $this->_supportableEntities[$this->_requestParams['thirdEntity']];
            $response = $this->_getResponse($model);
        } else {
            return ['status' => 'error','message' => 'Third entity not exists'];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return isset($response) ? $response : ['status' => 'error','message' => 'No requested data.'];
    }

    protected function _getResponse(ManagerInterface $model)
    {
        $method = \Yii::$app->request->getMethod();
        switch ($method)
        {
            case 'PUT':{
                $response = $model->put($this->_requestParams);
                break;
            }
            case 'OPTIONS':{
                $response = $model->options($this->_requestParams);
                break;
            }
            case 'PATCH':{
                $response = $model->patch($this->_requestParams);
                break;
            }
            default:{
                $response = ['status' => 'error', 'message' => 'Unknown request.'];
                break;
            }
        }
        return $response;
    }

    private function _getToken()
    {
        $dt = new DateTime('now',new DateTimeZone('UTC'));
        return crypt($dt->format('d/m/Y'),$this->_salt);
    }
}