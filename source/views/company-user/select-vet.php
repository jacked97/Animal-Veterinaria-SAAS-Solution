<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$companyData = \app\components\HelperFunctions::getCompanyData();
$vetsByCompany = \app\models\database\User::find()->where(['company_id' => $companyData->id])->all();

?>
<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css" media="print" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js"></script>

<style>
    .fc-day {
        background-color: background-color: rgba(0, 132, 0, 0.31);;
    }
</style>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Calendario - Scegli il Veterinario</h4>
            </div>
            <div class="content">
                <div>
                    <div class="list-group">
                        <?php foreach ($vetsByCompany as $key => $user): ?>
                            <a href="<?= Url::to(['', 'user_id' => $user->id]) ?>" class="list-group-item">
                                <?php
                                $imgUrl = $user->image;
                                if($imgUrl == "")
                                    $imgUrl = "default.png";
                                $imgUrl = Yii::getAlias('@web') . "/" . \app\controllers\VeterinarioController::$VET_IMAGE_PATH . $imgUrl;
                                ?>
                                <img src="<?= $imgUrl ?>"
                                     style="max-height: 100px; max-width: 80px;margin-right: 20px;"/>
                                <?= $user->lastName . ' - ' . $user->firstName ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
