<?php


/* @var string $cateoory
 * */

namespace backend\components;

use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use Yii;
use common\models\records\Image;
use Intervention\Image\ImageManager;

class Storage extends Component implements StorageInterFace
{
    private $filename;

    /**
     * @param UploadedFile $file
     * @param string $category
     * @param integer $modelId
     * @return $imageModel common\models\records\Image
     */
    public function saveUploadedFile(UploadedFile $file, string $category, $imageModel = null)
    {
        $model = (!$imageModel) ? new Image() : $imageModel;
        $model->fileName = str_replace('_', '-', $file->name);
        $model->fileHash = Yii::$app->getSecurity()->generateRandomString(32);
        $model->category = $category;
        $model->created = date("Y-m-d H:i:s");
        $model->modified = date("Y-m-d H:i:s");

        if ($model->save(false)) {
            $path = $this->preparePath($file, $category);
            $fileName = $this->createName($model->fileHash, $model->id);

            if (!$fileFormat = array_search($file->type, Image::MIME_FORMATS, true)) {
                throw new \Exception('Unknown image file type.');
            }

            $fullPath = FileHelper::normalizePath($path . DIRECTORY_SEPARATOR . $fileName . '.' . $fileFormat);

            if (FileHelper::createDirectory($path)) {
                $file->saveAs($fullPath);
                $manager = new ImageManager();
                $fileToSave = $manager->make($fullPath)->fit(200, 100)->save($fullPath);
                return $model->id;
            }
        }
    }

    protected function preparePath(UploadedFile $file, $category)
    {
        $mediaPath = Yii::$app->imageManager->mediaPath;
        $path = Yii::getAlias('@frontend') . '/web' . $mediaPath . DIRECTORY_SEPARATOR . $category;
        return $path;
    }

    protected function createName($fileName, $id)
    {
        $fileName = $id . '_' . $fileName;
        return $fileName;
    }

    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    public function getUploadedFile(string $filename)
    {
        return Yii::$app->params['storageUri'] . $filename;
    }
}