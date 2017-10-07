<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\database\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\staticcontent\StaticContentUser::$STATUS) ?>

    <label>Image</label><br/>
    <img id="image-view"  style="height: 200px;width: auto;display: none;" alt="your image"/>
    <?= $form->field($model, 'file')->fileInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'slot')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-view').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#user-file").change(function () {
        readURL(this);
        $("#image-view").show();
    });
</script>