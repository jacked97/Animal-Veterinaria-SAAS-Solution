<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $title
 * @property string $internet
 * @property string $username
 * @property string $address
 * @property string $city
 * @property string $zip
 * @property string $timestamp
 * @property string $slot
 * @property string $password
 * @property string $lastlogin
 * @property string $urlCode
 * @property integer $alert
 * @property string $accessToken
 * @property string $authKey
 *
 * @property Customer[] $customers
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp', 'lastlogin'], 'safe'],
            [['alert'], 'integer'],
            [['title', 'internet', 'username', 'address', 'city'], 'string', 'max' => 100],
            [['zip', 'slot'], 'string', 'max' => 10],
            [['password'], 'string', 'max' => 50],
            [['urlCode'], 'string', 'max' => 20],
//            [['accessToken', 'authKey'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'internet' => 'Internet',
            'username' => 'Username',
            'address' => 'Address',
            'city' => 'City',
            'zip' => 'Zip',
            'timestamp' => 'Timestamp',
            'slot' => 'Durata Slot prestazione',
            'password' => 'Password',
            'lastlogin' => 'Lastlogin',
            'urlCode' => 'Url Code',
            'alert' => 'Minuti prima invio avviso',
            'accessToken' => 'Access Token',
            'authKey' => 'Auth Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }
}
