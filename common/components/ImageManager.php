<?php

namespace common\components;

use common\models\records\Image;
use noam148\imagemanager\components\ImageManagerGetPath;
use Yii;

class ImageManager extends ImageManagerGetPath
{
    /**
     * @param Image $image
     * @param array $params
     * @return null|string
     */
    public function path($image, $params = [])
    {
        //default return
        $return = null;
        //check if not empty
        if ($image !== null) {
            if(isset($params['width'],$params['height'])){
                $width = $params['width'];
                $height = $params['height'];
                $thumbnailMode = $params['thumbnailMode'] ?? 'outbound';

                $imageFilePath = Yii::getAlias('@frontend') . '/web' . $image->getFilePath();
                //check file exists
                if (file_exists($imageFilePath)) {
                    $return = \Yii::$app->imageresize->getUrl($imageFilePath, $width, $height, $thumbnailMode, null, $image->fileName);
                } else {
                    $return = null;
                }
            } else {
                $return = $image->getFilePath();
            }
        }
        return $return;
    }
}