<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Custom Bootstrap asset bundle that points to the correct location.
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/bootstrap/dist';
    public $css = [
        'css/bootstrap.min.css',
    ];
    public $js = [
        'js/bootstrap.bundle.min.js',
    ];
    public $depends = [
        'frontend\assets\JqueryAsset',
    ];
} 