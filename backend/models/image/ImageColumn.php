<?php
namespace backend\models\image;

use Yii;
use yii\bootstrap\Html;
use yii\grid\Column;

class ImageColumn extends Column
{
    public $header = 'Image';

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index):string
    {
        /** @var \common\models\records\Image $model */
        return Html::img(Yii::$app->params['frontendUrl'] . $model->filePath,['class' => 'grid-custom-image']);
    }
}