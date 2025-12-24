<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/colors/blue.css',
        'css/site.css',
        'plugins/sweetalert/sweetalert.css'
    ];
    public $js = [
        'plugins/popper/popper.min.js',
        'plugins/bootstrap/js/bootstrap.min.js',
        'js/jquery.slimscroll.js',
        'js/waves.js',
        'js/sidebarmenu.js',
        'plugins/sticky-kit-master/dist/sticky-kit.min.js',
        'plugins/sparkline/jquery.sparkline.min.js',
        'js/custom.min.js',
        'plugins/styleswitcher/jQuery.style.switcher.js',
        'plugins/sweetalert/sweetalert.min.js',
        'js/confirmSwal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
