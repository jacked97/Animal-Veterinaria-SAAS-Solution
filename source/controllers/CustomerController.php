<?php

namespace app\controllers;

use app\components\HelperFunctions;
use app\DatabaseHelpers\DatabaseHelperCalendar;
use app\DatabaseHelpers\DatabaseHelperUsers;
use app\Emails\NewEventTemplate;
use app\models\custom\AppointmentForm;
use app\models\database\Calendar;
use app\models\database\User;
use yii\debug\Module;
use app\models\search\Calendar as CalendarSearch;
use app\Emails;

class CustomerController extends \yii\web\Controller
{
    private $companyData;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->companyData = HelperFunctions::getCompanyData();
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNewAppointment($user_id = null)
    {
        if ($user_id != null) {
            $calendarData = DatabaseHelperCalendar::retreiveCompanyCalendarDataForSpecificCustomer($this->companyData->id,
                \Yii::$app->user->id, $user_id);
            $newAppointmentForm = new AppointmentForm();

            return $this->render('new-appointment', [
                'calendarData' => $calendarData,
                'newAppointmentForm' => $newAppointmentForm
            ]);
        } else {
            $users = DatabaseHelperUsers::getAllUsersForSelect($this->companyData->id);
            return $this->render('/company-user/select-vet', [
                'users' => $users
            ]);
        }
    }

    public function actionCalendar($user_id = null)
    {
        $companyData = HelperFunctions::getCompanyData();
        if ($user_id != null) {
            $calendarData = DatabaseHelperCalendar::retreiveCompanyCalendarDataForSpecificCustomer(
                $companyData->id, \Yii::$app->user->id, $user_id
            );
            return $this->render('calendar', [
                'calendarData' => $calendarData,
                'companyData' => $this->companyData,
            ]);
        } else {
            $users = DatabaseHelperUsers::getAllUsersForSelect($this->companyData->id);
            return $this->render('/company-user/select-vet', [
                'users' => $users
            ]);
        }
    }

    public function actionEventHistory()
    {
        $searchFilter = array(
            'Calendar' => array('customer_id' => \Yii::$app->user->id)
        );
        \Yii::$app->request->queryParams = array_merge(\Yii::$app->request->queryParams, $searchFilter);

        $searchModel = new CalendarSearch();

//        HelperFunctions::output($_REQUEST);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('event-history', [
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionAjaxNewAppointment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $companyData = HelperFunctions::getCompanyData();
        $newAppointmentForm = new AppointmentForm();

        if ($newAppointmentForm->load(\Yii::$app->request->post())) {
            $storeCalendarEvent = new Calendar();
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

                $sendEmail = new NewEventTemplate();
                $calenderObject = Calendar::find()
                    ->where(['id' => $storeCalendarEvent->id])
                    ->with(['user', 'company', 'type', 'activity'])
                    ->one();
                // first notify the user who created the event
                $sendEmail->sendEmail($storeCalendarEvent, \Yii::$app->user->identity->username);
                //notify the vet/user for whom event created
                $sendEmail->sendEmail($storeCalendarEvent, User::findOne(['id' => $storeCalendarEvent->user_id])->email);

                echo json_encode(array('result' => '1'));
            } else {
                echo json_encode($storeCalendarEvent->errors);
            }
        }
    }

}
