<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property integer $id
 * @property string $date
 * @property string $hour_from
 * @property string $hour_to
 * @property integer $type_id
 * @property integer $activity_id
 * @property string $description
 * @property integer $user_id
 * @property integer $customer_id
 * @property integer $private
 * @property integer $company_id
 *
 * @property Activity $activity
 * @property Company $company
 * @property Customer $customer
 * @property Type $type
 * @property User $user
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['type_id', 'activity_id', 'user_id', 'customer_id', 'private', 'company_id', 'notified'], 'integer'],
            [['description'], 'string'],
            [['user_id', 'company_id'], 'required'],
            [['hour_from', 'hour_to'], 'string', 'max' => 10],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'hour_from' => 'Hour From',
            'hour_to' => 'Hour To',
            'type_id' => 'Type ID',
            'activity_id' => 'Activity ID',
            'description' => 'Description',
            'user_id' => 'User ID',
            'customer_id' => 'Customer ID',
            'private' => 'Private',
            'company_id' => 'Company ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
