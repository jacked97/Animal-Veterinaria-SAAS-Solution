<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/21/16
 * Time: 9:28 PM
 */

namespace app\DatabaseHelpers;


use app\components\HelperFunctions;
use app\models\database\Type;
use yii\helpers\ArrayHelper;

class DatabaseHelperType
{
    public static function typesDropDown()
    {
        $types =
            ArrayHelper::map(
                Type::find()->all(),
                'id', 'title');
        return $types;
    }
}