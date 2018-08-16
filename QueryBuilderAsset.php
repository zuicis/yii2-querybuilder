<?php

namespace leandrogehlen\querybuilder;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jquery QueryBuilder library](https://github.com/mistic100/jQuery-QueryBuilder)
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class QueryBuilderAsset extends AssetBundle {

    public $sourcePath = '@bower/jQuery-QueryBuilder/dist';
    
    public $js = [
        'js/query-builder.standalone.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.2.9/interact.min.js'
    ];

    public $css = [
        'css/query-builder.default.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'leandrogehlen\querybuilder\BootstrapAsset',
    ];

} 