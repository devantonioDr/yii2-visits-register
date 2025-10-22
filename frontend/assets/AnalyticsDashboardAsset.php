<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Analytics Dashboard Asset Bundle
 */
class AnalyticsDashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/analytics-dashboard.css',
    ];
    
    public $js = [
        'js/analytics-dashboard.js',
    ];
    
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
