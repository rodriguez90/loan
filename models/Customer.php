<?php

namespace app\models;

use Yii;
use Da\User\Model\User;

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
            [['created_by'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Número',
            'first_name' => 'Nombre',
            'last_name' => 'Apellidos',
            'dni' => 'Cedúla',
            'email' => 'Email',
            'phone_number' => 'Teléfono',
            'location' => 'Ubicación',
            'address' => 'Dirección',
            'created_at' => 'Fecha de Registro',
            'updated_at' => 'Fecha de Modificación',
            'created_by' => 'Creado Por',
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
