<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $dni
 * @property string $email
 * @property string $phone_number
 * @property string $location
 * @property string $address
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 *
 * @property User $createdBy
 * @property Loan[] $loans
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'dni', 'email', 'phone_number', 'location', 'address'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by'], 'integer'],
            [['first_name', 'last_name', 'dni', 'email', 'phone_number', 'location', 'address'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'dni' => 'Dni',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'location' => 'Location',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoans()
    {
        return $this->hasMany(Loan::className(), ['customer_id' => 'id']);
    }
}
