<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use \yii\bootstrap\Alert;

?>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">registrazione clienti</h4>
                <?= \app\components\HelperFunctions::flashMessage(); ?>
            </div>


            <div class="content">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                ]); ?>

                <?= $form->field($registrationForm, 'firstName')->textInput(['autofocus' => true]) ?>

                <?= $form->field($registrationForm, 'lastName')->textInput(['autofocus' => true]) ?>

                <?= $form->field($registrationForm, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($registrationForm, 'password')->passwordInput(['autofocus' => true]) ?>

                <?= $form->field($registrationForm, 'password_repeat')->passwordInput(['autofocus' => true]) ?>

                <?= $form->field($registrationForm, 'mobile')->textInput(['autofocus' => true]) ?>

                <div class="row">

                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'style' => 'width:100%']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>