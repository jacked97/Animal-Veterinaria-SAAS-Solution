<?php
/**
 * Created by PhpStorm.
 * User: abdullahmateen87
 * Date: 12/3/16
 * Time: 3:15 PM
 */

namespace app\models\custom;


use app\components\HelperFunctions;
use app\models\database\Customer;
use yii\base\Model;

class CustomerRegistration extends Model
{

    public $firstName, $lastName, $username, $password, $password_repeat, $mobile;
    public $companyData = null;

    public function attributeLabels()
    {
        return [
            'firstName' => 'Nome',
            'lastName' => 'cognome',
            'username' => 'Email',
            'password' => 'parola d\'ordine',
            'password_repeat' => 'ripeti la password',
            'mobile' => 'numero di cellulare',
        ];
    }

    public function rules()
    {
        return [
            // username and password are both required
            [['firstName', 'lastName', 'username', 'password', 'mobile'], 'required'],
            [['username'], 'email'],
            ['username', 'verifyCustomerEmailByCompanyID'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
            ['password', 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9]+)$/'
                , 'message' => 'require at least one upper-case letter and at least one digit (lower-case letters are not necessary)'],

        ];
    }


    public function verifyCustomerEmailByCompanyID()
    {
//        HelperFunctions::output("in shit");
        if ($this->username != "") {
            if ($this->companyData == null)
                $this->addError('username', 'Company Data not set in model.');
            else {
                if (Customer::findOne(['username' => $this->username, 'company_id' => $this->companyData->id]) != null)
                    $this->addError('username', 'Customer with the same email already exists.');
            }
        }
    }
}