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


<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.css" rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.print.css" media="print" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.0.1/fullcalendar.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/sweetalert2/6.1.1/sweetalert2.min.js"></script>


<style>
    .fc-time-grid-event.fc-short .fc-time:before {
        content: attr(data-full); /* ...instead, display only the start time */
    }
</style>


<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="header">
                <h4 class="title">Non prenotabile</h4>
            </div>

            <div class="content">

                <div>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php $form = ActiveForm::begin([
            'action' => ['ajax-private-slot-set'],
            'options' => ['id' => 'add-new-private-slots']
        ]); ?>
        <div style="display: none;">
            <?= $form->field($newPrivateSlot, 'hours_from')->hiddenInput() ?>
            <?= $form->field($newPrivateSlot, 'hours_to')->hiddenInput() ?>
            <?= $form->field($newPrivateSlot, 'veterinario')->hiddenInput() ?>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Set Slot Private</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Imposta con libero</button>
                <?= Html::submitButton('Imposta come non prenotatile', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button', 'style' => '']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="customized-date-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php $formCustomDate = ActiveForm::begin([
            'action' => ['ajax-private-slot-custom-date-set'],
            'options' => ['id' => 'add-new-custom-private-slots']
        ]); ?>
        <div style="display: none;">
            <?= $formCustomDate->field($newCustomPrivateSlot, 'veterinario')->hiddenInput() ?>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Set Custom Date Slot Private</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?= $formCustomDate->field($newCustomPrivateSlot, 'start_date')->textInput() ?>
                    </div>
                    <div class="col-md-6">
                        <?= $formCustomDate->field($newCustomPrivateSlot, 'end_date')->textInput() ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $formCustomDate->field($newCustomPrivateSlot, 'start_hour')->dropDownList(\app\staticcontent\StaticContentPrivateSlot::getAllHourInDay()) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $formCustomDate->field($newCustomPrivateSlot, 'end_hour')->dropDownList(\app\staticcontent\StaticContentPrivateSlot::getAllHourInDay()) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Imposta con libero</button>
                <?= Html::submitButton('Imposta come non prenotatile', ['class' => 'btn btn-primary tiny button', 'id' => 'submit-button-custom-private-date', 'style' => '']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="modal fade" id="existing-slots" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Appuntamenti presenti nel range selezionato </h4>
            </div>
            <div class="modal-body" style="height: 300px;overflow-y: scroll;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Hour</th>
                        <th>End Hour</th>
                        <th>Private</th>
                    </tr>
                    </thead>
                    <tbody id="table-tr-rows">
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    var calendar = null;
    var user_id = <?= $newPrivateSlot->veterinario ?>;
    $(document).ready(function () {

        var calendarData = <?= $calendarData ?>;
        var modal = $('#myModal');
        var appointmentform_hours_from = $("#appointmentform-hours_from");
        var appointmentform_hours_to = $("#appointmentform-hours_to");
        var daySlotSelected = false;

        var selected_time_from, selected_time_to;

//        log(calendarData.event_types["2016-11-16"]);
//        console.log(calendarData);

        calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            columnFormat: 'ddd D/M',
            defaultView: 'agendaWeek',
            selectable: true,
            selectHelper: true,
            expandThrough: false,
            events: calendarData.full_calendar_object,
            timezone: "<?= \app\components\HelperFunctions::getTimeZone()?>",
            selectHelper: true,
            scrollTime: '09:00:00',
            editable: true,
            eventOverlap: false,
            selectOverlap: false,
            dayRender: function (date, cell) {
                var currentDate = moment(date).format('YYYY-MM-DD');
                if (calendarData.event_types[currentDate] == true) {
                    cell.css("background-color", "rgba(128,128,128,0.4)");
//                    console.log("private");
                }
                else if (!(typeof calendarData.event_types[currentDate] == "undefined")) {
                    cell.css("background-color", "rgba(0, 128, 0, 0.4)");
//                    console.log("public");
                }
            },
            select: function (start, end, jsEvent, view) {
                if (start.hasTime()) {
                    modal.appendTo("body").modal('show');
                    var time_from = moment(start).format('YYYY-MM-DD HH:mm');
                    var time_to = moment(end).format('YYYY-MM-DD HH:mm');

                    selected_time_from = time_from;
                    selected_time_to = time_to;

                    appointmentform_hours_from.val(time_from);
                    appointmentform_hours_to.val(time_to);

//                console.log(time_from);
//                console.log(time_to);
//                if (window.confirm("Create this event?")) {
//                    $("#calendar").fullCalendar("removeEvents", "chunked-helper");
//                    $("#calendar").fullCalendar("addEventSource", chunk({start: start, end: end}, "event"));
//                    console.log(start, end);
//                } else {
//                    $("#calendar").fullCalendar("removeEvents", "chunked-helper");
//                }
                }
            },
            eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
                updateEventDB(event);
            },
            eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                updateEventDB(event);
            },
            eventClick: function (event, jsEvent, view) {
//                console.log(event);
                var sawalMy = swal.queue([{
                    title: 'Delete Event',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    text: 'Are you sure, you want to delete event ?',
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve) {
                            $.get('<?= Url::to(['company-user/ajax-delete-event']) ?>?event_id=' + event.id)
                                .done(function (data) {
                                    calendar.fullCalendar('removeEvents', [event.id]);
                                    swal.close();
                                })
                        })
                    }
                }]);
            },
            dayClick: function (date, jsEvent, view) {
                if (!date.hasTime())
                    addAllDayEvent(moment(date).format('YYYY-MM-DD'));
//                console.log("dayClick: ");
                console.log(date.hasTime());
            }

        });


        $('body').on('beforeSubmit', 'form#add-new-private-slots', function () {
            $("#appointmentform-veterinario").prop('disabled', false);
            var vetName = "Non prenotabile";
            $(this).find('[type="submit"]').toggleClass('m-progress');
            var form = $(this);
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
                    response = JSON.parse(response);
                    log(response);
                    if (response.result == 1) {

                        modal.modal('hide');
                        $.each(response.data, function (key, value) {
                            calendar.fullCalendar('renderEvent',
                                value,
                                true // make the event "stick"
                            );
                        });


                        calendar.fullCalendar('prev');
                        calendar.fullCalendar('next');
                        calendar.fullCalendar('rerenderEvents');
                        $("#add-new-private-slots").find('[type="submit"]').toggleClass('m-progress');


                    }
                },
                error: function () {
                    log(response)
                }
            });
            $("#appointmentform-veterinario").prop('disabled', true);
            return false;
        });


        function log(log) {
            console.log(log);
        }

        addBulkAddToLeftSideBar();

    });

    function updateEventDB(event) {
        var date = moment(event.start).format('YYYY-MM-DD');
        var startHour = moment(event.start).format('HH:mm');
        var endHour = moment(event.end).format('HH:mm');

        var data = {
            'Calendar[hour_from]': startHour,
            'Calendar[hour_to]': endHour,
            'Calendar[date]': date,
        };

        $.post("<?= Url::to(['company-user/ajax-edit-event']) ?>?event_id=" + event.id, data, function (data) {
            data = JSON.parse(data);
            if (data.result == 1)
                $.notify({
                    message: "Event data has been updated."

                }, {
                    type: 'success',
                    timer: 2000
                });
        });
    }

    function addAllDayEvent(date) {
//        var date = moment(event.start).format('YYYY-MM-DD');

        swal({
//            title: 'Are you sure?',
//            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Imposta come non prenotatile',
            cancelButtonText: 'Imposta con libero',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-warning',
            buttonsStyling: false
        }).then(function () {
            //make private
            var data = {
                'date': date,
                'user_id': user_id
            };
            $.post("<?= Url::to(['company-user/ajax-private-slot-set-all-day']) ?>", data, function (data) {
                data = JSON.parse(data);
                if (data.result == 1) {
                    customer = data.data.customer;
                    privateS = data.data.private;
                    if (customer.length > 0) {
                        var table = "<table class='table table-hover'>";
                        table += "<tr><td>Date</td><td>Hour From</td><td>Hour To</td></tr>";
                        $.each(data.data.customer, function (key, value) {
                            table += "<tr><td>" + value.date + "</td><td>" + value.hour_from + "</td><td>" + value.hour_to + "</td></tr>";
                        });
                        table += "</table>";

                        swal({
                            title: 'Customers event exist!',
                            html: table,
                            type: 'error'
                        });
                    } else if (privateS.length > 0) {
                        swal({
                            title: 'Are you sure?',
                            text: "questa azione annullerà le aree Non Prenotabili già presenti in questo giorno!!!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Imposta come non prenotatile!'
                        }).then(function () {
                            var data = {
                                'date': date,
                                'user_id': user_id,
                                'confirm': '1'
                            };
                            $.post("<?= Url::to(['company-user/ajax-private-slot-set-all-day']) ?>", data, function (data) {
                                data = JSON.parse(data);
                                calendar.fullCalendar('removeEvents', function (event) {
                                    console.log(date === moment(event.start).format('YYYY-MM-DD'));
                                    if (date === moment(event.start).format('YYYY-MM-DD'))
                                        return true;
                                });

                                calendar.fullCalendar('renderEvent',
                                    data.data,
                                    true // make the event "stick"
                                );
                            });
                        })
                    }
//                    console.log(customer);
//                    console.log(privateS);
                } else {
                    calendar.fullCalendar('renderEvent',
                        data.data,
                        true // make the event "stick"
                    );
                }

            });
        }, function (dismiss) {
            // set them free
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
                var data = {
                    'date': date,
                    'user_id': user_id
                };
                $.post("<?= Url::to(['company-user/ajax-free-slot-set-all-day']) ?>", data, function (data) {
                    data = JSON.parse(data);
//                    console.log(data);
                    if (data.result == 1) {
                        calendar.fullCalendar('removeEvents', function (event) {
                            console.log(date === moment(event.start).format('YYYY-MM-DD'));
                            if (date === moment(event.start).format('YYYY-MM-DD'))
                                return true;
                        });
                        $.notify({
                            message: "Event data has been updated."

                        }, {
                            type: 'success',
                            timer: 2000
                        });
                    } else {
                        var table = "<table class='table table-hover'>";
                        table += "<tr><td>Date</td><td>Hour From</td><td>Hour To</td></tr>";
                        $.each(data.data, function (key, value) {
                            table += "<tr><td>" + value.date + "</td><td>" + value.hour_from + "</td><td>" + value.hour_to + "</td></tr>";
                        });
                        table += "</table>";

                        swal({
                            title: 'Customers event exist!',
                            html: table,
                            type: 'error'
                        })
                    }
                });
            }
        });

//        var data = {
//            'Calendar[hour_from]': startHour,
//            'Calendar[hour_to]': endHour,
//            'Calendar[date]': date,
//        };
//
//        $.post("<?//= Url::to(['company-user/ajax-edit-event']) ?>//?event_id=" + event.id, data, function (data) {
//            data = JSON.parse(data);
//            if (data.result == 1)
//                $.notify({
//                    message: "Event data has been updated."
//
//                }, {
//                    type: 'success',
//                    timer: 2000
//                });
//        });
    }


    function selectVet(id) {
        $("#input-form-fields").show('200');
        $("#appointmentform-veterinario").val(id);
        $("#appointmentform-veterinario").prop('disabled', true);
        $("#select-veterinario").hide();
    }

    function afterSuccessEventAdded() {
        $("#submit-button").toggleClass('m-progress');
        $("#select-veterinario").show();
        $("#input-form-fields").hide();
        $('#add-new-appointment-form').trigger("reset");

    }

    function addBulkAddToLeftSideBar() {
//        alert("adding to left bar");
        $("#menu-side-bar").append('<li class=""><a onclick="$(\'#customized-date-modal\').appendTo(\'body\').modal(\'show\');"><i class="ti-pin2"></i><p>imposta fasce orarie</p></a></li>')
    }


</script>


<script>
    $('#customprivateslots-start_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#customprivateslots-end_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('body').on('beforeSubmit', 'form#add-new-custom-private-slots', function () {
        var formobj = this;
        var vetName = "Non prenotabile";
        $(this).find('[type="submit"]').toggleClass('m-progress');
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        $("#submit-button-custom-private-date").prop('disabled', true);
        // submit form
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (typeof response === 'string' || response instanceof String)
                    response = JSON.parse(response);
//                console.log(response);
                if (response.result == 1) {
//                    console.log(response);
                    $.each(response.data, function (key, value) {
//                        console.log(value);
                        calendar.fullCalendar('renderEvent',
                            value,
                            true // make the event "stick"
                        );
                    });

                    calendar.fullCalendar('prev');
                    calendar.fullCalendar('next');
                    calendar.fullCalendar('rerenderEvents');
                    $(formobj).find('[type="submit"]').toggleClass('m-progress');
                    $("#submit-button-custom-private-date").prop('disabled', false);
                    $('#add-new-custom-private-slots').trigger("reset");
                    $('#customized-date-modal').modal('hide');

                    var table = $("#table-tr-rows");
                    if (response.errors.length > 0) {
                        table.empty();
                        $.each(response.errors, function (key, value) {
                            appendErrorsToTable(table, value.date, value.startHour, value.endHour, value.private);
                        });
                        $("#existing-slots").modal('show');
                    }
                }
            },
            error: function () {
                log(response)
            }
        });
        return false;
    });

    function appendErrorsToTable(tableE, date, hour_from, hour_to, privat) {

        if (privat == "null" || privat == "0")
            privat = "By Customer";
        else {
            privat = "Private Slot";
        }

        var html = "<tr>";
        html += "<td>" + date + "</td>";
        html += "<td>" + hour_from + "</td>";
        html += "<td>" + hour_to + "</td>";
        html += "<td>" + privat + "</td>";
        html += "</tr>";
        tableE.append(html);
    }
</script>