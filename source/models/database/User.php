<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $timestamp
 * @property string $mobile
 * @property integer $status
 * @property string $image
 * @property string $slot
 * @property integer $company_id
 *
 * @property Calendar[] $calendars
 * @property Company $company
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $file;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['status', 'company_id'], 'integer'],
            [['company_id'], 'required'],
            [['firstName', 'lastName', 'email', 'image'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 50],
            [['slot'], 'string', 'max' => 10],
            ['file', 'file'],
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
            'email' => 'Email',
            'timestamp' => 'Timestamp',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'image' => 'Image',
            'slot' => 'Slot',
            'company_id' => 'Company ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
