<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $username
 * @property string $password
 * @property string $lastlogin
 * @property string $timestamp
 * @property string $mobile
 * @property integer $count
 * @property integer $company_id
 *
 * @property Calendar[] $calendars
 * @property Company $company
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lastlogin', 'timestamp'], 'safe'],
            [['count', 'company_id'], 'integer'],
            [['company_id'], 'required'],
            [['firstName', 'lastName', 'username'], 'string', 'max' => 100],
            [['password', 'mobile'], 'string', 'max' => 50],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'username' => 'Username',
            'password' => 'Password',
            'lastlogin' => 'Lastlogin',
            'timestamp' => 'Timestamp',
            'mobile' => 'Mobile',
            'count' => 'Count',
            'company_id' => 'Company ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
