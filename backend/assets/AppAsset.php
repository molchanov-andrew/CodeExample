<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\bootstrap\BootstrapAsset;
use yii\web\YiiAsset;
use yii\widgets\ActiveFormAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/hiddenItems.css',
        'css/lottery.css',
        'css/gridChangeMultiple.css',
    ];
    public $js = [
        'js/script.js',
        'js/modalGeneral.js',
        'js/hiddenItems.js',
        'js/ajaxSoloRows.js',
        'js/gridChangeMultiple.js',
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        ActiveFormAsset::class
    ];
}
