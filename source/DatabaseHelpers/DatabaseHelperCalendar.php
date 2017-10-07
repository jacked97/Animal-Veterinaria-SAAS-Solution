<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/19/16
 * Time: 6:54 PM
 */

namespace app\DatabaseHelpers;


use app\models\database\Calendar;

class DatabaseHelperCalendar
{

    public static function fullCalenderFormattedData($companId, $user_id)
    {
        /* Method for Admin*/
        $result = array();
        $eventType = array();
        $calenders = Calendar::find()
            ->where([
                'company_id' => $companId,
                'user_id' => $user_id
            ])
            ->with(['customer', 'type', 'activity', 'user'])
            ->all();

        foreach ($calenders as $calender) {

            $event = array();

            $event['id'] = $calender->id;
            $event['title'] = $calender->customer->lastName . ', ' . $calender->customer->firstName;

            $event['start'] = $calender->date . ' ' . $calender->hour_from;
            $event['end'] = $calender->date . ' ' . $calender->hour_to;

            if ($calender->private == 1) {
                $event['backgroundColor'] = 'rgba(128,128,128,0.4)';
                $event['borderColor'] = 'rgba(128,128,128,0.6)';
            } else {
                $event['backgroundColor'] = 'rgba(0, 128, 0, 0.4)';
                $event['borderColor'] = 'rgba(0, 128, 0, 0.6)';

                $event['eventData']['type'] = $calender->type->title;
                $event['eventData']['type_id'] = $calender->type_id;;
                $event['eventData']['activity'] = $calender->activity->title;
                $event['eventData']['activity_id'] = $calender->activity_id;
                $event['eventData']['description'] = $calender->description;
                $event['eventData']['user_full_name'] = $calender->user->lastName . " " . $calender->user->firstName;
                $event['eventData']['customer_last_name'] = $calender->customer->lastName;
                $event['eventData']['customer_first_name'] = $calender->customer->firstName;
                $event['eventData']['customer_mobile'] = $calender->customer->mobile;
                $event['eventData']['customer_email'] = $calender->customer->username;
                $event['eventData']['user_id'] = $calender->user_id;
                $event['eventData']['date'] = $calender->date;
                $event['eventData']['hour_to'] = $calender->hour_to;
                $event['eventData']['hour_from'] = $calender->hour_from;


            }

            array_push($result, $event);

            if (isset($eventType[$calender->date])) {
                if ($eventType[$calender->date] == 1)
                    $doNothing = false;
            } else
                $eventType[$calender->date] = $calender->private;

        }

        $jsonObj['full_calendar_object'] = $result;
        $jsonObj['event_types'] = $eventType;
        return json_encode($jsonObj);
    }


    public static function retreiveCompanyCalendarDataForSpecificCustomer($companId, $customerId, $userId)
    {
        /* Method for Customer*/
        $result = array();
        $eventDates = array();
        $calenders = Calendar::find()
            ->where([
                'company_id' => $companId,
                'user_id' => $userId
            ])
            ->with(['customer'])
            ->all();

        foreach ($calenders as $calender) {

            $event = array();

            $event['title'] = "";

            //if event doesn't belongs to user, then show a gray box;
            if ($calender->customer_id == $customerId) {
                $event['title'] = $calender->customer->lastName . ', ' . $calender->customer->firstName;
                $event['backgroundColor'] = 'rgba(0, 128, 0, 0.4)';
                $event['borderColor'] = 'rgba(0, 128, 0, 0.6)';

                $event['eventData']['type'] = $calender->type->title;
                $event['eventData']['activity'] = $calender->activity->title;
                $event['eventData']['description'] = $calender->description;
                $event['eventData']['user_full_name'] = $calender->user->lastName . " " . $calender->user->firstName;
                $event['eventData']['customer_last_name'] = $calender->customer->lastName;
                $event['eventData']['customer_first_name'] = $calender->customer->firstName;
                $event['eventData']['customer_mobile'] = $calender->customer->mobile;
                $event['eventData']['customer_email'] = $calender->customer->username;
            } else {
                $event['backgroundColor'] = 'rgba(128,128,128,0.4)';
                $event['borderColor'] = 'rgba(128,128,128,0.6)';

            }


            $event['start'] = $calender->date . ' ' . $calender->hour_from;
            $event['end'] = $calender->date . ' ' . $calender->hour_to;


            array_push($result, $event);

        }

        $jsonObj['full_calendar_object'] = $result;
        return json_encode($jsonObj);
    }
}