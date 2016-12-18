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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.js"></script>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Nuovo appuntamento</h4>
            </div>


            <div class="content">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
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
                <h4 class="modal-title" id="myModalLabel">Add New Appointment</h4>
            </div>
            <div class="modal-body">
                <div id="input-form-fields" style="display: block;">

                    <div class="hidden-fields" style="display: none;">
                        <?= $form->field($newAppointmentForm, 'date')->hiddenInput() ?>
                        <?= $form->field($newAppointmentForm, 'hours_from')->hiddenInput() ?>
                        <?= $form->field($newAppointmentForm, 'hours_to')->hiddenInput() ?>
                    </div>

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
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button-new-appointment', 'style' => '']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>

    var timeDifferenceAllowedMinutes = <?= $companyData->slot; ?>;
    var slotErrorMessage = 'La tua visita prevede una durata di [' + timeDifferenceAllowedMinutes +
        '] min ed è presente un\'altra visita che si sovrappone con la tua. Ti suggeriamo di cambiare orario.';

    $(document).ready(function () {

        var calendarData = <?= $calendarData ?>;
        var addNewEventCurrentState = {};
        var user_vet_id = <?= $_REQUEST['user_id'] ?>;

        var appointmentform_date = $("#appointmentform-date");
        var appointmentform_hours_from = $("#appointmentform-hours_from");
        var appointmentform_hours_to = $("#appointmentform-hours_to");

        var modal = $('#myModal');


        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultView: 'agendaWeek',
            slotDuration: '00:' + timeDifferenceAllowedMinutes + ':00',
            scrollTime: '09:00:00',
            eventStartEditable: false,
            selectable: true,
            selectHelper: true,
            timezone: "local",
            eventOverlap: false,
            selectOverlap: false,
//            editable: false,
//            disableResizing: true,
            select: function (start, end, allDay) {

                addNewEventCurrentState.start = start;
                end = moment(start);
                end.add(timeDifferenceAllowedMinutes, 'minutes');
                console.log(start._d);
                console.log(end._d);

                var eventsOnDate = isDateHasEvent(start, end);
//                log(eventsOnDate);
                if (eventsOnDate.length > 0) {
//                    log(isDateHasEvent(start, end));
                    swal(
                        'Time Slots Limit',
                        slotErrorMessage,
                        'question'
                    )
                    return false;
                }

                addNewEventCurrentState.end = end;
                addNewEventCurrentState.allDay = allDay;


                var date = moment(start).format('YYYY-MM-DD');
                var time_from = moment(start).format('HH:mm');
                var time_to = moment(end).format('HH:mm');

                appointmentform_date.val(date);
                appointmentform_hours_from.val(time_from);
                appointmentform_hours_to.val(time_to);

                selectVet(user_vet_id);
                modal.appendTo("body").modal('show');

            },
            eventResize: function (event, delta, revertFunc) {

                alert(event.title + " end is now " + event.end.format());


            },
            navLinks: true, // can click day/week names to navigate views
            eventLimit: true, // allow "more" link when too many events
            events: calendarData.full_calendar_object,

        });


        function log(log) {
            console.log(log);
        }


        $('body').on('beforeSubmit', 'form#add-new-appointment-form', function () {
            $("#appointmentform-veterinario").prop('disabled', false);
            var vetName = $('#appointmentform-veterinario option:selected').text();
            $(this).find('[type="submit"]').toggleClass('m-progress');
            var form = $(this);
            $("#submit-button-new-appointment").prop('disabled', true);
            // return false if form still have some validation errors
            if (form.find('.has-error').length) {
                return false;
            }
            // submit form
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (response) {
                    log(response);
                    if (typeof response === 'string' || response instanceof String)
                        response = JSON.parse(response);
                    if (response.result == 1) {

                        $("#submit-button-new-appointment").prop('disabled', false);
                        $(form).find('[type="submit"]').toggleClass('m-progress');
                        modal.modal('hide');


                        calendar.fullCalendar('renderEvent',
                            {
                                title: vetName,
                                start: addNewEventCurrentState.start,
                                end: addNewEventCurrentState.end,
                                backgroundColor: "rgba(0, 128, 0, 0.4)",
                                borderColor: "rgba(0, 128, 0, 0.6)"
                            },
                            true // make the event "stick"
                        );

                        calendar.fullCalendar('unselect');
                        calendar.fullCalendar('prev');
                        calendar.fullCalendar('next');
                        afterSuccessEventAdded();
                    }
                },
                error: function () {
                    log(response)
                }
            });
            $("#appointmentform-veterinario").prop('disabled', true);
            return false;
        });
    });

    function selectVet(id) {
        $("#appointmentform-veterinario").val(id);
        $("#appointmentform-veterinario").prop('disabled', true);
    }

    function afterSuccessEventAdded() {
        $("#submit-button").toggleClass('m-progress');
        $('#add-new-appointment-form').trigger("reset");

    }

    function isDateHasEvent(start, end) {
        start = moment(start);
        end = moment(end);
        var foundEvent = [];
        var allEvents = [];
        allEvents = $('#calendar').fullCalendar('clientEvents');
        $.each(allEvents, function (key, value) {
//            console.log(value);
            var startCal = moment(value.start);
            var endCal = moment(value.end);
            if (start.isBetween(startCal, endCal) || end.isBetween(startCal, endCal)) {
                foundEvent.push(value);
            }
        });
        return foundEvent;
    }

</script>