<?php
namespace backend\models\image;

use Yii;
use yii\bootstrap\Html;
use yii\grid\Column;

class RelativeImageColumn extends Column
{
    public $header = 'Image';
    public $imageField;

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index):string
    {
        $imageField = $this->imageField;
        /** @var \common\models\records\Image $model */
        return $imageField !== null && !empty($model->$imageField) ? Html::img(Yii::$app->params['frontendUrl'] . $model->$imageField->filePath,['class' => 'grid-custom-image']) : 'Not set';
    }
}