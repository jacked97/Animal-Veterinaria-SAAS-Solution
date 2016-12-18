<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/19/16
 * Time: 3:37 PM
 */

namespace app\DatabaseHelpers;


use app\components\HelperFunctions;
use app\models\database\User;
use yii\helpers\ArrayHelper;

class DatabaseHelperUsers
{
    public static function getAllUsersForSelect($companyId)
    {

        $users =
            ArrayHelper::map(
                User::find()->where(['company_id' => $companyId])->all(),
                'id',
                function ($model, $defaultValue) {
                    return $model['lastName'] . ' - ' . $model['firstName'];
                });
        return $users;
    }
}