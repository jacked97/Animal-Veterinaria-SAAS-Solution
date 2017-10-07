<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/21/16
 * Time: 9:28 PM
 */

namespace app\DatabaseHelpers;


use app\models\database\Activity;
use yii\helpers\ArrayHelper;

class DatabaseHelperActivity
{
    public static function activityDropDown()
    {
        $types =
            ArrayHelper::map(
                Activity::find()->all(),
                'id', 'title');
        return $types;
    }
}