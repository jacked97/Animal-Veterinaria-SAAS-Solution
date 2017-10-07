<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \app\controllers\CompanyController;
use \yii\helpers\Url;

$this->title = 'Login';
?>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">

            <div class="header">
                <h4 class="title"><?= Html::encode($this->title . " " . \app\models\User::$typeS) ?></h4>
            </div>

            <div class="content">

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <!---->
                <!--                --><? //= $form->field($model, 'rememberMe')->checkbox([
                //                    'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                //                ]) ?>

                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-2">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                    <div class=" col-lg-2">
                        <?php if (isset($fromCompanyController)): ?>
                            <?php
                            $companyId = CompanyController::$companyId;
                            ?>
                            <a href="<?= Url::to(["company/$companyId/registration"]) ?>">
                                <Button class="btn btn-primary" type="button">Register</Button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>