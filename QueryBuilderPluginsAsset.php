<?php
namespace leandrogehlen\querybuilder;
use yii\web\AssetBundle;

class QueryBuilderPluginsAsset extends AssetBundle
{
    public $options = [];



    public $publishOptions = [
        'forceCopy'=> true,
        'appendTimestamp' => true,
    ];

    public function init(){
        $this -> sourcePath = __DIR__ . '/assets';
        parent::init();        
    }


    public $js = [
        'js/interact.min.js',
    ];
} 
