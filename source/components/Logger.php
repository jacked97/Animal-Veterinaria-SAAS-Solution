<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 12/2/16
 * Time: 9:06 PM
 */

namespace app\components;


use app\models\database\Logs;

class Logger
{

    public static $EMAIL_LOG = 'EMAIL_LOG';

    public static function log($string, $type)
    {
        $storeLog = new Logs();
        $storeLog->text = $type . ' : ' . $string;
        $storeLog->save();
    }

}