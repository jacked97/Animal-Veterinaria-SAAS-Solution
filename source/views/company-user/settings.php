<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Impostazioni</h4>
            </div>

            <div class="content">

                <div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'layout' => 'horizontal',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-1\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
                    ]); ?>

                    <?= $form->field($companyModel, 'slot')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($companyModel, 'alert')->textInput(['autofocus' => true]) ?>

                    <div class="row">

                        <div class="col-md-3">

                        </div>
                        <div class="col-md-3">
                            <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'style' => 'width:100%']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>