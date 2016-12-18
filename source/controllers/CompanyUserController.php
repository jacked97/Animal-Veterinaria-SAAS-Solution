<?php

namespace app\controllers;

use app\components\HelperFunctions;
use app\DatabaseHelpers\DatabaseHelperCalendar;
use app\DatabaseHelpers\DatabaseHelperUsers;
use app\models\custom\AppointmentForm;
use app\models\custom\CustomPrivateSlots;
use app\models\database\Calendar;
use app\models\database\Company;
use app\models\database\User;
use app\models\search\Calendar as CalendarSearch;
use yii\base\DynamicModel;

class CompanyUserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $companyModel = Company::findOne(['id' => \Yii::$app->user->id]);
//        HelperFunctions::output(\Yii::$app->request->post());

        if ($companyModel->load(\Yii::$app->request->post()) && $companyModel->save()) {
            return $this->redirect(['settings']);
        }

        return $this->render('settings', [
            'companyModel' => $companyModel
        ]);
    }

    public function actionEventList()
    {
        $newAppointmentForm = new AppointmentForm();

        $modelForFormDays = new DynamicModel(['days', 'veterinario', 'from_date', 'to_date', 'private']);
        $modelForFormDays->addRule(['days'], 'required');
        $modelForFormDays->addRule(['days', 'veterinario'], 'number');
        $modelForFormDays->addRule(['from_date', 'to_date'], 'date');

        $modelForFormDays->days = 15;
        $modelForFormDays->private = 0;

        $modelForFormDays->load($_REQUEST);


        $searchModel = new CalendarSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, $modelForFormDays);


        return $this->render('event-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelForFormDays' => $modelForFormDays,
            'newAppointmentForm' => $newAppointmentForm
        ]);
    }


    /*
     * Action: Event list actions
     */

    public function actionUpdate($id, $user_id, $company_id)
    {
        $checkRecordBelongsToCurrentCompany = Calendar::findOne([
            'id' => $id,
            'company_id' => $company_id,
            'user_id' => $user_id
        ]);
        if ($checkRecordBelongsToCurrentCompany != null) {
            if ($checkRecordBelongsToCurrentCompany->load(array('Calendar' => $_POST['AppointmentForm']))) {
                if ($checkRecordBelongsToCurrentCompany->save()) {

                } else {
                    HelperFunctions::output($checkRecordBelongsToCurrentCompany->errors);
                }
            }


            $appointmentEditForm = new AppointmentForm();
            $appointmentEditForm->date = $checkRecordBelongsToCurrentCompany->date;
            $appointmentEditForm->hours_to = $checkRecordBelongsToCurrentCompany->hour_to;
            $appointmentEditForm->hours_from = $checkRecordBelongsToCurrentCompany->hour_from;
            $appointmentEditForm->type = $checkRecordBelongsToCurrentCompany->type_id;
            $appointmentEditForm->activity = $checkRecordBelongsToCurrentCompany->activity_id;
            $appointmentEditForm->veterinario = $checkRecordBelongsToCurrentCompany->user_id;
            $appointmentEditForm->description = $checkRecordBelongsToCurrentCompany->description;


            return $this->render('edit-event', [
                'appointmentEditForm' => $appointmentEditForm
            ]);
        }
    }

    public function actionDelete($id, $user_id, $company_id)
    {
        $checkRecordBelongsToCurrentCompany = Calendar::findOne([
            'id' => $id,
            'company_id' => $company_id,
            'user_id' => $user_id
        ]);
        if ($checkRecordBelongsToCurrentCompany != null) {
            $checkRecordBelongsToCurrentCompany->delete();
            return $this->redirect('event-list');
        }
    }

    public function actionCalendar($user_id = null)
    {
        if ($user_id != null) {
            $calendarData = DatabaseHelperCalendar::fullCalenderFormattedData(\Yii::$app->user->id, $user_id);
            $newAppointmentForm = new AppointmentForm();
            return $this->render('calendar', [
                'calendarData' => $calendarData,
                'newAppointmentForm' => $newAppointmentForm
            ]);
        } else {
            $users = DatabaseHelperUsers::getAllUsersForSelect(\Yii::$app->user->id);
            return $this->render('select-vet', [
                'users' => $users
            ]);
        }
    }

    public function actionPrivateSlot($user_id = null)
    {
        if ($user_id != null) {

            $calendarData = DatabaseHelperCalendar::fullCalenderFormattedData(\Yii::$app->user->id, $user_id);
            $newPrivateSlot = new AppointmentForm();
            $newCustomPrivateSlot = new CustomPrivateSlots();
            $newPrivateSlot->veterinario = $user_id;
            $newCustomPrivateSlot->veterinario = $user_id;
            return $this->render('private-slot', [
                'calendarData' => $calendarData,
                'newPrivateSlot' => $newPrivateSlot,
                'newCustomPrivateSlot' => $newCustomPrivateSlot
            ]);

        } else {
            $users = DatabaseHelperUsers::getAllUsersForSelect(\Yii::$app->user->id);
            return $this->render('select-vet', [
                'users' => $users
            ]);
        }
    }

    public function actionAjaxPrivateSlotSet()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyData = HelperFunctions::getCompanyData();
        $newPrivateSlot = new AppointmentForm();

        $addedSlots = array();

        if ($newPrivateSlot->load(\Yii::$app->request->post())) {
            $startDate = new \DateTime($newPrivateSlot->hours_from);
            $endDate = new \DateTime($newPrivateSlot->hours_to);

            while ($startDate <= $endDate) {

                $storePrivateSlot = new Calendar();
                $storePrivateSlot->date = $startDate->format('Y-m-d');
                $storePrivateSlot->hour_from = $startDate->format('H:i');
                $storePrivateSlot->hour_to = $endDate->format('H:i');
                $storePrivateSlot->private = 1;
                $storePrivateSlot->description = "Pianifica";
                $storePrivateSlot->user_id = $newPrivateSlot->veterinario;
                $storePrivateSlot->company_id = $companyData->id;

                if (!$storePrivateSlot->save()) {
                    echo json_encode($storePrivateSlot->errors);
                    break;
                    exit;
                }

                $resultSlot['start'] = $storePrivateSlot->date . " " . $storePrivateSlot->hour_from;
                $resultSlot['end'] = $storePrivateSlot->date . " " . $storePrivateSlot->hour_to;
                $resultSlot['title'] = $storePrivateSlot->description;
                $resultSlot['backgroundColor'] = 'rgba(128,128,128,0.4)';
                $resultSlot['borderColor'] = 'rgba(128,128,128,0.6)';
                array_push($addedSlots, $resultSlot);


                $startDate->modify('+1 day');
            }

            //print_r($newPrivateSlot);
        }
        echo json_encode(array('result' => 1, 'data' => $addedSlots));
    }

    public function actionAjaxPrivateSlotCustomDateSet()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyData = HelperFunctions::getCompanyData();
        $newPrivateSlot = new CustomPrivateSlots();
        $errorsArr = array();

        $addedSlots = array();

        if ($newPrivateSlot->load(\Yii::$app->request->post())) {
            $startDate = new \DateTime($newPrivateSlot->start_date . " " . $newPrivateSlot->start_hour);
            $endDate = new \DateTime($newPrivateSlot->end_date . " " . $newPrivateSlot->end_hour);

            while ($startDate <= $endDate) {

                $date = $startDate->format('Y-m-d');
                $startHour = $startDate->format('H:i');
                $endHour = $endDate->format('H:i');

                $storePrivateSlot = new Calendar();
                $storePrivateSlot->date = $date;
                $storePrivateSlot->hour_from = $startHour;
                $storePrivateSlot->hour_to = $endHour;
                $storePrivateSlot->private = 1;
                $storePrivateSlot->description = "Pianifica";
                $storePrivateSlot->user_id = $newPrivateSlot->veterinario;
                $storePrivateSlot->company_id = $companyData->id;

                $errors = $this->checkIfEventExsist($date, $startHour, $endHour, $companyData, $newPrivateSlot->veterinario);

                if (!$errors) {
                    if (!$storePrivateSlot->save()) {
                        echo json_encode($storePrivateSlot->errors);
                        break;
                        exit;
                    }
                } else {
                    $errorsArr = array_merge($errors, $errorsArr);
                }

                $resultSlot['start'] = $storePrivateSlot->date . " " . $storePrivateSlot->hour_from;
                $resultSlot['end'] = $storePrivateSlot->date . " " . $storePrivateSlot->hour_to;
                $resultSlot['title'] = $storePrivateSlot->description;
                $resultSlot['backgroundColor'] = 'rgba(128,128,128,0.4)';
                $resultSlot['borderColor'] = 'rgba(128,128,128,0.6)';

                if ($errors == false)
                    array_push($addedSlots, $resultSlot);


                $startDate->modify('+1 day');
            }

            //print_r($newPrivateSlot);
        }
        echo json_encode(array('result' => 1, 'data' => $addedSlots, 'errors' => $errorsArr));
    }

    public function actionAjaxEditEvent($event_id)
    {
        $event = Calendar::findOne(['id' => $event_id]);
        if ($event != null) {
//            HelperFunctions::output($_POST);
            if ($event->load(\Yii::$app->request->post()) && $event->save()) {
                echo json_encode(array('result' => 1));
            }
        }
    }

    public function actionAjaxDeleteEvent($event_id)
    {
        $companyData = HelperFunctions::getCompanyData();
        $event = Calendar::findOne([
            'id' => $event_id,
            'company_id' => $companyData->id
        ]);
        if ($event != null) {
            Calendar::deleteAll(['id' => $event_id, 'company_id' => $companyData->id]);
            echo json_encode(array('result' => 1));
        }
    }

    public function actionAjaxUpdateAppointment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyData = HelperFunctions::getCompanyData();
        $newAppointmentForm = new AppointmentForm();

        if ($newAppointmentForm->load(\Yii::$app->request->post())) {
//            HelperFunctions::output($newAppointmentForm);
            $storeCalendarEvent = Calendar::findOne(['id' => $newAppointmentForm->id]);
            $storeCalendarEvent->customer_id = \Yii::$app->user->id;
            $storeCalendarEvent->company_id = $companyData->id;
            $storeCalendarEvent->date = $newAppointmentForm->date;
            $storeCalendarEvent->hour_from = $newAppointmentForm->hours_from;
            $storeCalendarEvent->hour_to = $newAppointmentForm->hours_to;
            $storeCalendarEvent->type_id = $newAppointmentForm->type;
            $storeCalendarEvent->activity_id = $newAppointmentForm->activity;
            $storeCalendarEvent->description = $newAppointmentForm->description;
            $storeCalendarEvent->user_id = $newAppointmentForm->veterinario;

            if ($storeCalendarEvent->save()) {
                $getStoredEvent = Calendar::find()
                    ->where(['id' => $newAppointmentForm->id])
                    ->with(['customer', 'type', 'activity', 'user'])
                    ->one();
                $event = array();
                $event['type'] = $getStoredEvent->type->title;
                $event['type_id'] = $getStoredEvent->type_id;;
                $event['activity'] = $getStoredEvent->activity->title;
                $event['activity_id'] = $getStoredEvent->activity_id;
                $event['description'] = $getStoredEvent->description;
                $event['user_full_name'] = $getStoredEvent->user->lastName . " " . $getStoredEvent->user->firstName;
                $event['customer_last_name'] = $getStoredEvent->customer->lastName;
                $event['customer_first_name'] = $getStoredEvent->customer->firstName;
                $event['customer_mobile'] = $getStoredEvent->customer->mobile;
                $event['customer_email'] = $getStoredEvent->customer->username;
                $event['user_id'] = $getStoredEvent->user_id;
                $event['date'] = $getStoredEvent->date;
                $event['hour_to'] = $getStoredEvent->hour_to;
                $event['hour_from'] = $getStoredEvent->hour_from;


                echo json_encode(array('result' => '1', 'eventData' => $event));
            } else {
                echo json_encode($storeCalendarEvent->errors);
            }
        }
    }

    public function actionAjaxGetEventData($event_id)
    {
        $companyData = HelperFunctions::getCompanyData();
        $calender = Calendar::findOne(['id' => $event_id, 'company_id' => $companyData->id]);

        if ($calender != null) {
            $event = array();
            $event['id'] = $calender->id;
            $event['type_id'] = $calender->type_id;;
            $event['activity_id'] = $calender->activity_id;
            $event['description'] = $calender->description;
            $event['user_id'] = $calender->user_id;
            $event['date'] = $calender->date;
            $event['hour_to'] = $calender->hour_to;
            $event['hour_from'] = $calender->hour_from;
            echo json_encode(array('result' => '1', 'eventData' => $event));
        } else {
            echo json_encode(array('result' => '0'));
        }
    }

    private function checkIfEventExsist($date, $startHour, $endHour, $companyData, $user_id)
    {
        $exsistingEvents = array();
        $calenderData = Calendar::find()
            ->where("date = '$date' AND hour_from >= '$startHour' AND hour_to <= '$endHour'")
            ->andWhere(['company_id' => $companyData->id, 'user_id' => $user_id])
            ->all();
        if ($calenderData != null) {
            foreach ($calenderData as $cal) {
                array_push($exsistingEvents, array(
                    'date' => $cal->date, 'startHour' => $cal->hour_from, 'endHour' => $cal->hour_to,
                    'private' => $cal->private
                ));
            }
            return $exsistingEvents;
        } else {
            return false;
        }

    }


}
