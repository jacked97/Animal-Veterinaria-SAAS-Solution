<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 12/4/16
 * Time: 4:12 PM
 */

namespace app\models\custom;


use yii\base\Model;

class CustomPrivateSlots extends Model
{
    public $start_date, $end_date, $start_hour, $end_hour, $veterinario;

    public function attributeLabels()
    {
        return [
            'start_date' => 'data d\'inizio',
            'end_date' => 'data di fine',
            'start_hour' => 'iniziare ora',
            'end_hour' => 'ora fine',
        ];
    }

    public function rules()
    {
        return [
            // username and password are both required
            [['start_date', 'end_date', 'start_hour', 'end_hour','veterinario'], 'required'],
//            [['end_hour'], 'compare', 'compareAttribute' => 'start_hour', 'operator' => '>='],

            // rememberMe must be a boolean value
        ];
    }
}