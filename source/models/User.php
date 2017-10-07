<?php

namespace app\models;

use app\components\HelperFunctions;
use app\config\KeyConfig;
use app\models\database\Company;
use app\models\database\Customer;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public
        $id,
        $email,
        $password,
        $lastlogin,
        $timestamp;

    //company specific attributes
    public $title,
        $internet,
        $address,
        $city,
        $zip,
        $slot,
        $urlCode,
        $alert;

    //customer specific attributes
    public $firstName,
        $lastName,
        $mobile,
        $count,
        $company_id;


    public $username;
//    public $password;
    public $authKey;
    public $accessToken;


    public $type = '';

    public static $typeS, $companyId ;
    public static $customer = 'customer', $company = 'company';

    public function __construct($config)
    {
        $this->type = $_SESSION[KeyConfig::$userTypeSessionKey];
        parent::__construct($config);
    }


    public static function findIdentity($id)
    {
//        HelperFunctions::output($_SESSION);
        $dbUser = null;
        if (!isset(User::$typeS))
            self::$typeS = $_SESSION[KeyConfig::$userTypeSessionKey];
        if (self::$typeS == self::$company)
            $dbUser = Company::find()
                ->where([
                    "id" => $id
                ])
                ->one();
        if (self::$typeS == self::$customer)
            $dbUser = Customer::find()
                ->where([
                    "id" => $id
                ])
                ->one();
//        print_r($_SESSION);
//        HelperFunctions::output($dbUser);
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $dbUser = Company::find()
            ->where(["accessToken" => $token])
            ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if (!isset(User::$companyId))
            self::$companyId = $_SESSION[KeyConfig::$userCompanyIdKey];

//        HelperFunctions::output(self::$companyId.$username);

        if (self::$typeS == self::$company)
            $dbUser = Company::find()
                ->where([
                    "username" => $username
                ])
                ->one();
        if (self::$typeS == self::$customer)
            $dbUser = Customer::find()
                ->where([
                    "username" => $username,
                    'company_id' => self::$companyId
                ])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function isAdmin()
    {
        if ($this->type == self::$ADMIN)
            return true;
        else
            return false;
    }

    public function isUser()
    {
        if ($this->type == self::$USER)
            return true;
        else
            return false;
    }
}
