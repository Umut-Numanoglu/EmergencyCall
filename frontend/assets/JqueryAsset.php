<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Custom jQuery asset bundle that points to the correct location.
 */
class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/jquery/dist';
    public $js = [
        'jquery.min.js',
    ];
} 