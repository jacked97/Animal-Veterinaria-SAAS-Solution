<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use \yii\grid\GridView;
use \yii\widgets\Pjax;

//\app\components\HelperFunctions::output(\app\DatabaseHelpers\DatabaseHelperUsers::getAllUsersForSelect());


$companyData = \app\components\HelperFunctions::getCompanyData();
$veterinarioSelect = \app\DatabaseHelpers\DatabaseHelperUsers::getAllUsersForSelect($companyData->id);

$this->registerJs(
    '$("document").ready(function(){
        $("#new_country").on("pjax:end", function() {
            $.pjax.reload({container:"#event-lists"});  //Reload GridView
        });
    });'
);

?>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Elenco Appuntamenti</h4>
            </div>
            <div class="content">
                <?php Pjax::begin(['id' => 'event-lists']); ?>


                <div>
                    <div id="days-filter" style="display: block;">
                        <?php $form = ActiveForm::begin(['options' => [
                            'id' => 'login-form',
                            'method' => 'GET',
                            'data-pjax' => true
                        ]]); ?>

                        <label>Filters: </label>

                        <div class="row">
                            <div class="col-md-2">
                                <?= $form->field($modelForFormDays, 'days')->textInput()->label("Show next XX days") ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($modelForFormDays, 'veterinario')->dropDownList(
                                    \app\DatabaseHelpers\DatabaseHelperUsers::getAllUsersForSelect(Yii::$app->user->id),
                                    array('prompt' => 'Select Veterinario')
                                )->label("Visualizza per Veterinario") ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($modelForFormDays, 'from_date')->textInput()->label("Date From") ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($modelForFormDays, 'to_date')->textInput()->label("Date To") ?>
                            </div>
                            <div class="col-md-3" style="margin-top: 20px;">
                                <div class="col-md-6" style="padding:0px;padding-left: 1px;margin-top: 2px;">
                                    <a href="<?= Url::to(['company-user/event-list']); ?>">
                                        <button class="btn btn-primary" style="width:100%" type="button">Reset</button>
                                    </a>
                                </div>
                                <div class="col-md-6" style="padding:0px;padding-left: 1px;margin-top: 2px;">
                                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary', 'style' => 'width:100%']) ?>
                                </div>

                            </div>

                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div style="overflow-x: scroll;">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

//                            'id',
                                'date',
                                'hour_from',
                                'hour_to',
                                [
                                    'label' => 'Customer Name',
                                    'value' => function ($data) {
//                        \app\components\HelperFunctions::output($data);
                                        return $data->customer->lastName . ' - ' . $data->customer->firstName;
                                    }
                                ],
                                [
                                    'label' => 'Animale',
                                    'value' => function ($data) {
                                        return $data->type->title;
                                    }
                                ],
                                [
                                    'label' => 'Servizio erogato',
                                    'value' => function ($data) {
                                        return $data->activity->title;
                                    }
                                ],
                                [
                                    'label' => 'Veterinario',
                                    'value' => function ($data) {
                                        return $data->user->lastName . ' - ' . $data->user->firstName;
                                    }
                                ],
                                // 'activity_id',
                                // 'description:ntext',
                                // 'user_id',
                                // 'customer_id',
                                // 'private',
                                [
                                    'attribute' => 'Edit',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return "<a onclick='editEventModal(" . $model->id . ")'>" .
                                        "<img style='height:32px;' src='" . Yii::getAlias('@web') . "/icons/edit_icon.png'/>" .
                                        "</a>";
                                    }
                                ],
                                [
                                    'attribute' => 'Delete',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return "<a href='" . Url::to(['delete', 'id' => $model->id, 'user_id' => $model->user_id, 'company_id' => $model->company_id]) . "'>" .
                                        "<img style='height:32px;' src='" . Yii::getAlias('@web') . "/icons/delete_icon.png'/>" .
                                        "</a>";
                                    }
                                ],
//                            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

                            ]]); ?>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="eventEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['ajax-update-appointment'],
            'options' => ['id' => 'update-appointment-form']
        ]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update an Event</h4>
            </div>
            <div class="modal-body">
                <div id="input-form-fields" style="display: none;">
                    <?= $form->field($newAppointmentForm, 'id')->hiddenInput()->label(false) ?>
                    <?= $form->field($newAppointmentForm, 'date')->textInput(['placeholder' => 'Loading...']) ?>
                    <?= $form->field($newAppointmentForm, 'hours_from')->textInput(['placeholder' => 'Loading...']) ?>
                    <?= $form->field($newAppointmentForm, 'hours_to')->textInput(['placeholder' => 'Loading...']) ?>

                    <?= $form->field($newAppointmentForm, 'type')->dropDownList(
                        \app\DatabaseHelpers\DatabaseHelperType::typesDropDown(), array('prompt' => 'Select Tipo animale')
                    ) ?>
                    <?= $form->field($newAppointmentForm, 'activity')->dropDownList(
                        \app\DatabaseHelpers\DatabaseHelperActivity::activityDropDown(), array('prompt' => 'Select Servizio')
                    ) ?>
                    <?= $form->field($newAppointmentForm, 'description')->textarea(['placeholder' => 'Loading...']) ?>
                    <?= $form->field($newAppointmentForm, 'veterinario')->dropDownList(
                        $veterinarioSelect,
                        array('prompt' => 'Select Veterinario')
                    ) ?>


                </div>

                <h3 style="text-align: center;" id="event-loading-heading">Loading Event Data...</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button-update-appointment', 'style' => '']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    $(function () {
        $("#dynamicmodel-from_date").datepicker({dateFormat: 'yy-mm-dd'});
        $("#dynamicmodel-to_date").datepicker({dateFormat: 'yy-mm-dd'});

    });
    var editModal = $("#eventEditModal");
    var event_loading_heading = $("#event-loading-heading");
    var input_form_fields = $("#input-form-fields");

    var formappointmentform_date = $("#appointmentform-date");
    var appointmentform_hours_from = $("#appointmentform-hours_from");
    var appointmentform_hours_to = $("#appointmentform-hours_to");
    var appointmentform_type = $("#appointmentform-type");
    var appointmentform_activity = $("#appointmentform-activity");
    var appointmentform_description = $("#appointmentform-description");
    var appointmentform_veterinario = $("#appointmentform-veterinario");
    var appointmentform_id = $("#appointmentform-id");
    var submit_button_update_appointment = $("#submit-button-update-appointment");

    function editEventModal(eventId) {
        $.get("<?= Url::to(['ajax-get-event-data']) ?>", {event_id: eventId})
            .done(function (data) {
                if (typeof data === 'string' || data instanceof String)
                    data = JSON.parse(data);
                if (data.result == 1) {
                    event_loading_heading.hide();
                    input_form_fields.show();
                    var eventData = data.eventData;
                    formappointmentform_date.val(eventData.date);
                    appointmentform_hours_from.val(eventData.hour_from);
                    appointmentform_hours_to.val(eventData.hour_to);
                    appointmentform_type.val(eventData.type_id);
                    appointmentform_activity.val(eventData.activity_id);
                    appointmentform_description.val(eventData.description);
                    appointmentform_veterinario.val(eventData.user_id);
                    appointmentform_id.val(eventData.id);
                }
                console.log(data);
            });
        editModal.appendTo("body").modal('show');
    }

    $('body').on('beforeSubmit', 'form#update-appointment-form', function () {
        $(this).find('[type="submit"]').toggleClass('m-progress');
        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                log(response);
                if (typeof response === 'string' || response instanceof String)
                    response = JSON.parse(response);
                if (response.result == 1) {

                    submit_button_update_appointment.prop('disabled', false);
                    $(form).find('[type="submit"]').toggleClass('m-progress');
                    editModal.modal('hide');


                    form.trigger("reset");
                    $.pjax.reload({container: '#event-lists'});

                    $.notify({
                        message: "Event data has been updated."

                    }, {
                        type: 'success',
                        timer: 2000
                    });
                }
            },
            error: function () {
                log(response)
            }
        });
        return false;
    });


    function log(log) {
        console.log(log);
    }

</script>