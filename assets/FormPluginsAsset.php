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
        'js/plugins/parsley/src/parsley.css',

    ];
    public $js = [
        'js/plugins/parsley/dist/parsley.js',
        'js/plugins/parsley/dist/i18n/es.js',
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}