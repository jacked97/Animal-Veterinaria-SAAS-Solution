<?php

namespace app\controllers;

use app\components\HelperFunctions;
use app\components\Logger;
use app\Emails\EventNotificationsTemplate;
use app\Emails\NewEventTemplate;
use app\models\search\Calendar;

class CronJobController extends \yii\web\Controller
{
    /* get all events and compare with company notified minutes */
    private $NOTIFICATION_TIME_SAFE_SIDE = 3; //defining a safe side which can be occur for cron job, email delay etc.


    public function actionIndex()
    {
        Logger::log('Cron Job Started - Event Notifications', Logger::$EMAIL_LOG);

        $currentDateTime = HelperFunctions::currMysqlDateTime();
//        HelperFunctions::output(\Yii::$app->getTimeZone());

        $calendarEvents = Calendar::find()
            ->innerJoin('company', 'calendar.company_id = company.id')
            ->where("(calendar.notified IS NULL OR calendar.notified = 0)")
            ->with(['user', 'company', 'type', 'activity'])
            ->andWhere("(TIMESTAMPDIFF(SECOND, '$currentDateTime', CONCAT(date,' ', hour_from)) <
             ( (company.alert + $this->NOTIFICATION_TIME_SAFE_SIDE) * 60))")
            ->all();

        foreach ($calendarEvents as $event) {
            $eventNotification = new EventNotificationsTemplate();
            $eventNotification->sendEmail($event, $event->customer->username);
            $eventNotification->sendEmail($event, $event->user->email);
            $eventNotification->updateDatabase($event);
        }

        Logger::log('Cron Job End - Event Notifications', Logger::$EMAIL_LOG);

    }


}
