<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $loan_id
 * @property int $collector_id
 * @property string $payment_date
 * @property double $amount
 *
 * @property User $collector
 * @property Loan $loan
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loan_id', 'collector_id', 'payment_date', 'amount'], 'required'],
            [['loan_id', 'collector_id'], 'integer'],
            [['amount'], 'number'],
            [['payment_date'], 'string', 'max' => 255],
            [['collector_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['collector_id' => 'id']],
            [['loan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Loan::className(), 'targetAttribute' => ['loan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loan_id' => 'Loan ID',
            'collector_id' => 'Collector ID',
            'payment_date' => 'Payment Date',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollector()
    {
        return $this->hasOne(User::className(), ['id' => 'collector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoan()
    {
        return $this->hasOne(Loan::className(), ['id' => 'loan_id']);
    }
}
