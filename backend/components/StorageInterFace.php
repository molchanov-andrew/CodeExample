<?php
namespace backend\components;

use  yii\web\UploadedFile;

interface StorageInterFace
{
    public function saveUploadedFile(UploadedFile $file, string $category);

    public function getUploadedFile(string $filename);
}