<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 12/2/16
 * Time: 6:21 PM
 */

namespace app\Emails;


use app\components\Logger;

class NewEventTemplate
{
    private $templatePath = '../Emails/Templates/new-event.html';
    private $subject = 'Nuovo evento realizzato';
    private $template = '';

    private $ATTR_CREATED_BY = "{{CREATED-BY}}";
    private $ATTR_DESCRIPTION = "{{DESCRIPTION}}";
    private $ATTR_SCHEDULED_FROM = "{{SCHEDULED-FROM}}";
    private $ATTR_SCHEDULED_TO = "{{SCHEDULED-TO}}";
    private $ATTR_TYPE = "{{TYPE}}";
    private $ATTR_SERVICE = "{{SERVICE}}";
    private $ATTR_USER = "{{USER}}";

    private $fromEmail = '';
    private $fromName = '';

    public function __construct()
    {
        $this->template = file_get_contents($this->templatePath);
        $this->fromEmail = \Yii::$app->params['adminEmail'];
        $this->fromName =  \Yii::$app->params['adminEmail'];
    }


    public function sendEmail($calendarObject, $to)
    {
        $this->template = str_replace($this->ATTR_CREATED_BY, $calendarObject->customer->lastName . ", " . $calendarObject->customer->firstName, $this->template);
        $this->template = str_replace($this->ATTR_USER, $calendarObject->user->lastName . ", " . $calendarObject->user->firstName, $this->template);
        $this->template = str_replace($this->ATTR_DESCRIPTION, $calendarObject->description, $this->template);
        $this->template = str_replace($this->ATTR_SCHEDULED_FROM, $calendarObject->date . " " . $calendarObject->hour_from, $this->template);
        $this->template = str_replace($this->ATTR_SCHEDULED_TO, $calendarObject->hour_to, $this->template);
        $this->template = str_replace($this->ATTR_SCHEDULED_TO, $calendarObject->hour_to, $this->template);
        $this->template = str_replace($this->ATTR_TYPE, $calendarObject->type->title, $this->template);
        $this->template = str_replace($this->ATTR_SERVICE, $calendarObject->activity->title, $this->template);

        \Yii::$app->mailer->compose()
            ->setTo($to)
            ->setFrom([$this->fromEmail => $this->fromName])
            ->setSubject($this->subject)
            ->setHtmlBody($this->template)
            ->send();

        Logger::log("Calender Object ID: $calendarObject->id, Email Sent to: $to", Logger::$EMAIL_LOG);
    }

}