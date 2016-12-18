<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/17/16
 * Time: 7:48 PM
 */

namespace app\components;


use app\models\database\Company;
use app\models\User;
use Yii;

class HelperFunctions
{
    public static function output($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }

    public static function getCompanyData()
    {
        $companyData = null;
        $user = \Yii::$app->user;
        if (!$user->isGuest) {
            if ($user->identity->type == User::$company)
                $companyData = Company::findOne(['id' => $user->id]);
            else
                $companyData = Company::findOne(['id' => $user->identity->company_id]);
        }
        return $companyData;
    }

    public static function currMysqlDateTime()
    {
        return date("Y-m-d H:i:s");
    }

    public static function flashMessage()
    {
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<p class="alert alert-' . $key . '">' . $message[0] . '</p>';
        }
    }
}