<?php
namespace backend\models\response;

use Yii;
use yii\base\BaseObject;
use yii\web\Response;

class AjaxResponse extends BaseObject
{
    const STATUS_SUCCESS = 'success';
    const STATUS_WARNING = 'warning';
    const STATUS_ERROR = 'error';

    public $status = 'success';
    public $message;
    public $data;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function render()
    {
        Yii::$app->response->setStatusCode(200);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}