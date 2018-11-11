<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 07/11/2018
 * Time: 22:52
 */

namespace app\assets;

use yii\web\AssetBundle;


class FormPluginsAsset extends  AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [


    ];
    public $js = [
   
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}