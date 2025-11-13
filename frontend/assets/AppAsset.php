<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Frontend App Asset Bundle
 * @since 0.1
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/site.css?v=1.0',
        'css/styles.css?v=1.0',
        'css/google-fonts.css',
        'css/ionicons/css/ionicons.css',
    ];
    
    public $js = [
        'js/imask.js',
        'js/site.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
    ];
}
