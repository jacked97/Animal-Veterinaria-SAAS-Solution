<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 11/21/16
 * Time: 9:01 PM
 */

namespace app\models\custom;


use yii\base\Model;

class AppointmentForm extends Model
{

    public $type, $activity, $description, $veterinario, $hours_from, $hours_to, $date, $id;

    public function attributeLabels()
    {
        return [
            'type' => 'Tipo animale',
            'activity' => 'Servizio',
            'description' => 'Descrizione',
            'veterinario' => 'Veterinario',
        ];
    }

    public function rules()
    {
        return [
            // username and password are both required
            [['type', 'activity', 'description', 'veterinario', 'hours_from', 'hours_to', 'date','id'], 'required'],
            // rememberMe must be a boolean value
        ];
    }
}