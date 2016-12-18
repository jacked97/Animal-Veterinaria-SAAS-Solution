<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

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
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var calendarData = <?= $calendarData ?>;
        var modal = $('#myModal');
        var timeDifferenceAllowedMinutes = <?= $companyData->slot; ?>;

        var modalFieldsType = $("#modal-view-appointment-type");
        var modalFieldsActivity = $("#modal-view-appointment-activity");
        var modalFieldsUser = $("#modal-view-appointment-user");
        var modalFieldsDescription = $("#modal-view-appointment-description");
        var modalFieldsCustomerFirstname = $("#modal-view-appointment-customer-firstname");
        var modalFieldsCustomerLastname = $("#modal-view-appointment-customer-lastname");
        var modalFieldsCustomerMobile = $("#modal-view-appointment-customer-mobile");
        var modalFieldsCustomerEmail = $("#modal-view-appointment-customer-email");

        log(calendarData.full_calendar_object);
        $('#calendar').fullCalendar({
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
            scrollTime: '09:00:00',
            slotDuration: '00:' + timeDifferenceAllowedMinutes + ':00',
            defaultView: 'agendaWeek',
            timezone: "local",
            dayRender: function (date, cell) {
//                console.log(date._d);
                var currentDate = moment(date).format('YYYY-MM-DD');


//                var verify_event = has_event_is_private(moment(date).format('YYYY-MM-DD'));
//                console.log(verify_event);


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

                    modal.appendTo("body").modal('show');
                }

            }

        });
//        has_event_is_private('1234');


        function log(log) {
            console.log(log);
        }


    });


</script>