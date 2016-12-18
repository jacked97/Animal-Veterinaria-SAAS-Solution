<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$companyData = \app\components\HelperFunctions::getCompanyData();
$veterinarioSelect = \app\DatabaseHelpers\DatabaseHelperUsers::getAllUsersForSelect($companyData->id);

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
                <h4 class="title">Calendario</h4>
            </div>


            <div class="content">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['ajax-new-appointment'],
            'options' => ['id' => 'add-new-appointment-form']
        ]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Event</h4>
            </div>
            <div class="modal-body">

                <div id="input-form-fields" style="display: block;">


                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Tipo Animale
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-type"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Servizio richiesto
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-activity"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Veterinario
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-user"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Descrizione
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-description"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer first name
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-firstname"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer last name
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-lastname"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer Mobile No.
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-mobile"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer Email
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-email"></p>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="editEventModal()" class="btn btn-default">Edit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
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
                <div id="input-form-fields" style="display: block;">
                    <?= $form->field($newAppointmentForm, 'id')->hiddenInput()->label(false) ?>
                    <?= $form->field($newAppointmentForm, 'date')->textInput(['readonly' => true]) ?>
                    <?= $form->field($newAppointmentForm, 'hours_from')->textInput(['readonly' => true]) ?>
                    <?= $form->field($newAppointmentForm, 'hours_to')->textInput(['readonly' => true]) ?>

                    <?= $form->field($newAppointmentForm, 'type')->dropDownList(
                        \app\DatabaseHelpers\DatabaseHelperType::typesDropDown(), array('prompt' => 'Select Tipo animale')
                    ) ?>
                    <?= $form->field($newAppointmentForm, 'activity')->dropDownList(
                        \app\DatabaseHelpers\DatabaseHelperActivity::activityDropDown(), array('prompt' => 'Select Servizio')
                    ) ?>
                    <?= $form->field($newAppointmentForm, 'description')->textarea(['autofocus' => true]) ?>
                    <?= $form->field($newAppointmentForm, 'veterinario')->dropDownList(
                        $veterinarioSelect,
                        array('prompt' => 'Select Veterinario')
                    ) ?>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button-update-appointment', 'style' => '']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['ajax-new-appointment'],
            'options' => ['id' => 'add-new-appointment-form']
        ]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Event</h4>
            </div>
            <div class="modal-body">

                <div id="input-form-fields" style="display: block;">


                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Tipo Animale
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-type"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Servizio richiesto
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-activity"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Veterinario
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-user"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Descrizione
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-description"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer first name
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-firstname"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer last name
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-lastname"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer Mobile No.
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-mobile"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>
                                Customer Email
                            </label>
                        </div>
                        <div class="col-md-8">
                            <p id="modal-view-appointment-customer-email"></p>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="editEventModal()" class="btn btn-default">Edit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    var modal = $('#myModal');
    var calendarData = <?= $calendarData ?>;
    var currentEventObj = null;
    var fullCalendarObj = null;
    $(document).ready(function () {


        var modalFieldsType = $("#modal-view-appointment-type");
        var modalFieldsActivity = $("#modal-view-appointment-activity");
        var modalFieldsUser = $("#modal-view-appointment-user");
        var modalFieldsDescription = $("#modal-view-appointment-description");
        var modalFieldsCustomerFirstname = $("#modal-view-appointment-customer-firstname");
        var modalFieldsCustomerLastname = $("#modal-view-appointment-customer-lastname");
        var modalFieldsCustomerMobile = $("#modal-view-appointment-customer-mobile");
        var modalFieldsCustomerEmail = $("#modal-view-appointment-customer-email");

        log(calendarData.event_types["2016-11-16"]);
        console.log(calendarData);
        fullCalendarObj = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
//            defaultDate: '2016-09-12',
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: calendarData.full_calendar_object,
            timezone: "local",
            scrollTime: '09:00:00',
            dayRender: function (date, cell) {
//                console.log(date._d);
                var currentDate = moment(date).format('YYYY-MM-DD');


//                var verify_event = has_event_is_private(moment(date).format('YYYY-MM-DD'));
//                console.log(verify_event);

                if (calendarData.event_types[currentDate] == true) {
                    cell.css("background-color", "rgba(128,128,128,0.4)");
                    console.log("private");
                }
                else if (!(typeof calendarData.event_types[currentDate] == "undefined")) {
                    cell.css("background-color", "rgba(0, 128, 0, 0.4)");
                    console.log("public");
                }


            },
            eventClick: function (calEvent, jsEvent, view) {
                console.log(calEvent);
                if (typeof calEvent.eventData != 'undefined') {
                    var eventData = calEvent.eventData;
                    modalFieldsType.text(eventData.type);
                    modalFieldsActivity.text(eventData.activity);
                    modalFieldsUser.text(eventData.user_full_name);
                    modalFieldsDescription.text(eventData.description);
                    modalFieldsCustomerFirstname.text(eventData.customer_first_name);
                    modalFieldsCustomerLastname.text(eventData.customer_last_name);
                    modalFieldsCustomerMobile.text(eventData.customer_mobile);
                    modalFieldsCustomerEmail.text(eventData.customer_email);
                    currentEventObj = calEvent;
                    modal.appendTo("body").modal('show');

                }

            }

        });
//        has_event_is_private('1234');


        function log(log) {
            console.log(log);
        }


    });


    var editModal = $("#eventEditModal");
    var formappointmentform_date = $("#appointmentform-date");
    var appointmentform_hours_from = $("#appointmentform-hours_from");
    var appointmentform_hours_to = $("#appointmentform-hours_to");
    var appointmentform_type = $("#appointmentform-type");
    var appointmentform_activity = $("#appointmentform-activity");
    var appointmentform_description = $("#appointmentform-description");
    var appointmentform_veterinario = $("#appointmentform-veterinario");
    var appointmentform_id = $("#appointmentform-id");
    var submit_button_update_appointment = $("#submit-button-update-appointment");

    function editEventModal() {
        var eventData = currentEventObj.eventData;
        if (currentEventObj != null) {
            formappointmentform_date.val(eventData.date);
            appointmentform_hours_from.val(eventData.hour_from);
            appointmentform_hours_to.val(eventData.hour_to);
            appointmentform_type.val(eventData.type_id);
            appointmentform_activity.val(eventData.activity_id);
            appointmentform_description.val(eventData.description);
            appointmentform_veterinario.val(eventData.user_id);
            appointmentform_id.val(currentEventObj.id);
            console.log("event id: " + currentEventObj.id);
        }
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
                    modal.modal('hide');

                    currentEventObj.eventData = response.eventData;

                    fullCalendarObj.fullCalendar('updateEvent', event);

                    form.trigger("reset");
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