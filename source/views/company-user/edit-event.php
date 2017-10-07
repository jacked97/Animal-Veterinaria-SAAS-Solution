<?php

use \app\components\HelperFunctions;
use \app\DatabaseHelpers\DatabaseHelperUsers;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$companyData = HelperFunctions::getCompanyData();
$veterinarioSelect = DatabaseHelperUsers::getAllUsersForSelect($companyData->id);


?>

<div>
    <h2>Modifica evento</h2>

    <div class="col-md-7">
        <?php $form = ActiveForm::begin([

        ]); ?>

        <?= $form->field($appointmentEditForm, 'date')->textInput() ?>
        <?= $form->field($appointmentEditForm, 'hours_from')->textInput() ?>
        <?= $form->field($appointmentEditForm, 'hours_to')->textInput() ?>

        <?= $form->field($appointmentEditForm, 'type')->dropDownList(
            \app\DatabaseHelpers\DatabaseHelperType::typesDropDown(), array('prompt' => 'Select Tipo animale')
        ) ?>
        <?= $form->field($appointmentEditForm, 'activity')->dropDownList(
            \app\DatabaseHelpers\DatabaseHelperActivity::activityDropDown(), array('prompt' => 'Select Servizio')
        ) ?>
        <?= $form->field($appointmentEditForm, 'description')->textarea(['autofocus' => true]) ?>
        <?= $form->field($appointmentEditForm, 'veterinario')->dropDownList(
            $veterinarioSelect,
            array('prompt' => 'Select Veterinario')
        ) ?>

        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button', 'style' => '']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>