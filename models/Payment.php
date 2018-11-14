<?php

namespace app\models;

use Yii;
use Da\User\Model\User;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $loan_id
 * @property int $collector_id
 * @property string $payment_date
 * @property string $amount
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 *
 * @property User $collector
 * @property Loan $loan
 */
class Payment extends \yii\db\ActiveRecord
{

    const PENDING = 0;
    const COLLECTED = 1;

    const STATUS_LABEL = [
        0 => 'Pendiente',
        1 => 'Cobrado',
    ];

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
            [['loan_id', 'collector_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'id' => 'No.',
            'loan_id' => 'Préstamo',
            'collector_id' => 'Cobrador',
            'payment_date' => 'Fecha de pago',
            'amount' => 'Cuota',
            'created_at' => 'Fecha Registro',
            'updated_at' => 'Fehca Modificación',
            'status' => 'Estado',
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
