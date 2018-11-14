<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 14/11/2018
 * Time: 9:03
 */

namespace app\assets;

use yii\web\AssetBundle;


class DataTableAsset extends AssetBundle
{
//    public $sourcePath = '@vendor/';
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
//        'js/plugins/DataTables/media/css/datatables.css',
        'js/plugins/DataTables/media/css/dataTables.bootstrap.css',
    ];
    public $js = [
        'js/plugins/DataTables/media/js/datatables.js',
        'js/plugins/DataTables/media/js/dataTables.bootstrap.js'
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}