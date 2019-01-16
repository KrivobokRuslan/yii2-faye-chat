<?php

namespace krivobokruslan\fayechat\assets;

use yii\web\AssetBundle;

class ChatAssets extends AssetBundle
{
    public $sourcePath = '@vendor/krivobokruslan/yii2-faye-chat/src';

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/main.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}