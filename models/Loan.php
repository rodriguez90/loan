<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loan".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $banker_id
 * @property double $amount
 * @property double $porcent_interest
 * @property int $status
 * @property int $refinancing_id
 * @property int $frequency_payment
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $banker
 * @property Customer $customer
 * @property Loan $refinancing
 * @property Loan[] $loans
 * @property Payment[] $payments
 */
class Loan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'banker_id', 'amount', 'porcent_interest', 'status', 'frequency_payment', 'start_date', 'end_date'], 'required'],
            [['customer_id', 'banker_id', 'status', 'refinancing_id', 'frequency_payment'], 'integer'],
            [['amount', 'porcent_interest'], 'number'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['banker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['banker_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['refinancing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Loan::className(), 'targetAttribute' => ['refinancing_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'banker_id' => 'Banker ID',
            'amount' => 'Amount',
            'porcent_interest' => 'Porcent Interest',
            'status' => 'Status',
            'refinancing_id' => 'Refinancing ID',
            'frequency_payment' => 'Frequency Payment',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanker()
    {
        return $this->hasOne(User::className(), ['id' => 'banker_id']);
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
    public function getRefinancing()
    {
        return $this->hasOne(Loan::className(), ['id' => 'refinancing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoans()
    {
        return $this->hasMany(Loan::className(), ['refinancing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['loan_id' => 'id']);
    }
}
