<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/site.css',
        'js/plugins/jquery-confirm/jquery-confirm.min.css'
    ];
    public $js = [
        'js/utils.js',
        'js/plugins/moment/moment.min.js',
        'js/plugins/jquery-confirm/jquery-confirm.min.js'
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}
