<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets-dashboard/css/bootstrap.min.css',
        'assets-dashboard/css/animate.min.css',
        'assets-dashboard/css/paper-dashboard.css',
        'assets-dashboard/css/demo.css',
        'http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css',
        'https://fonts.googleapis.com/css?family=Muli:400,300',
        'assets-dashboard/css/themify-icons.css',
        '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
    ];
    public $js = [
        'assets-dashboard/js/bootstrap.min.js',
        'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
        'js/notify.min.js',
        'assets-dashboard/js/bootstrap-checkbox-radio.js',
        'assets-dashboard/js/chartist.min.js',
        'assets-dashboard/js/bootstrap-notify.js',
        'https://maps.googleapis.com/maps/api/js',
        'assets-dashboard/js/paper-dashboard.js',
        'assets-dashboard/js/demo.js',
        ''
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
