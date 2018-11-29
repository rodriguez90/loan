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
        'js/plugins/DataTables/media/css/dataTables.bootstrap.min.css',
        'js/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css',
        'js/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css',
//        'js/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css'	,
        'js/plugins/DataTables/extensions/KeyTable/css/keyTable.bootstrap.min.css',
        'js/plugins/DataTables/extensions/Checkboxes/dataTables.checkboxes.css',
        'js/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.css',
        'js/plugins/DataTables/extensions/FixedHeader/css/fixedHeader.bootstrap.css',
    ];
    public $js = [
        'js/plugins/DataTables/media/js/jquery.dataTables.min.js',
        'js/plugins/DataTables/media/js/datatables.min.js',
        'js/plugins/DataTables/media/js/dataTables.bootstrap.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js',
        'js/plugins/DataTables/extensions/Buttons/js/buttons.colVis.min.js',
        'js/plugins/DataTables/extensions/Select/js/dataTables.select.min.js',
        'js/plugins/DataTables/extensions/KeyTable/js/dataTables.keyTable.min.js',
//        'js/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js',
//        'js/plugins/DataTables/extensions/Responsive/js/responsive.bootstrap.min.js',
//        'js/plugins/DataTables/extensions/CellEdit/js/dataTables.cellEdit.js',
        'js/plugins/DataTables/extensions/Checkboxes/dataTables.checkboxes.min.js',
        'js/plugins/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}