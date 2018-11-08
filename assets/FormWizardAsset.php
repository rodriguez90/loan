<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 07/11/2018
 * Time: 8:03
 */

namespace app\assets;

use yii\web\AssetBundle;


class FormWizardAsset extends  AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/plugins/parsley/src/parsley.css',
        'js/plugins/bootstrap-wizard/css/bwizard.css',

    ];
    public $js = [
        'js/plugins/parsley/dist/parsley.js',
        'js/plugins/parsley/dist/i18n/es.js',
        'js/plugins/bootstrap-wizard/js/bwizard.js',

        'js/customer/create-form-wizards-validation.js'
    ];
    public $depends = [
        'app\assets\AppAsset'
    ];
}