<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/17/16
 * Time: 8:12 PM
 */

namespace app\staticcontent;


use app\components\HelperFunctions;
use app\controllers\CompanyController;
use app\models\User;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;

class Navbar
{
    private $customers = array(
        array('label' => 'Nuovo appuntamento', 'url' => ['customer/new-appointment'], 'icon' => 'ti-plus'),
        array('label' => 'Calendario', 'url' => ['customer/calendar'], 'icon' => 'ti-calendar'),
        array('label' => 'Storico', 'url' => ['customer/event-history'], 'icon' => 'ti-view-list-alt'),
    );
    private $company = array(
//        array('label' => 'Impostazioni', 'url' => ['company-user/settings']),
        array('label' => 'Veterinario', 'url' => ['veterinario/index'], 'icon' => 'ti-user'),
        array('label' => 'Elenco Appuntamenti', 'url' => ['company-user/event-list'], 'icon' => 'ti-view-list-alt'),
        array('label' => 'Calendario', 'url' => ['company-user/calendar'], 'icon' => 'ti-calendar'),
        array('label' => 'Pianifica', 'url' => ['company-user/private-slot'], 'icon' => 'ti-pin2'),
    );

    public function navbar()
    {
//        \Yii::$app->user->isGuest ?: (
//        $menu = [array('label' => 'Logout', 'url' => ['site/logout'])]
//        );
//        HelperFunctions::output();
        $menu = array();

        if (\Yii::$app->user->isGuest && \Yii::$app->controller->id == 'company') {
            $companyId = CompanyController::$companyId;
            $menu = [
                ['label' => 'Accesso cliente', 'url' => ["company/$companyId/login", 'type' => 'customer']],
                ['label' => 'login società', 'url' => ["company/$companyId/login", 'type' => 'company']],
                ['label' => 'registrazione del cliente', 'url' => ["company/$companyId/registration"]],
            ];
        } elseif (\Yii::$app->user->isGuest) {
            $menu = [
                ['label' => 'login società', 'url' => ["site/login", 'type' => 'company']],
            ];
        } elseif (\Yii::$app->user->identity->type == User::$company)
            $menu = array_merge($this->company, $menu);
        elseif (\Yii::$app->user->identity->type == User::$customer)
            $menu = array_merge($this->customers, $menu);

//        HelperFunctions::output($menu);
        if (sizeof($menu) > 0)
            return $menu;
        else
            return null;
    }

    public function generateHTML($array)
    {
        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;

//        HelperFunctions::output($controller . "/" . $action);
        $html = "<ul class=\"nav\"  id=\"menu-side-bar\">";
        foreach ($array as $key => $value) {

            $label = $value["label"];
            $url = $value["url"][0];
//            HelperFunctions::output($url);
            $active = "";
            if ($url == $controller . "/" . $action)
                $active = "active";

            $html .= "<li class=\"$active\">"
                . "<a href=\"" . Url::to($value["url"]) . "\">"
                . "<i class=\"" . $value["icon"] . "\"></i>"
                . "<p>" . $label . "</p>"
                . "</a>"
                . "</li>";

        }
        $html .= "</ul>";
        return $html;
    }


}