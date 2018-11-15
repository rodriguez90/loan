<?php

namespace app\models;

use Da\User\Model\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `app\models\Payment`.
 *
 * @property string $collectorName
 */
class PaymentSearch extends Payment
{
    public $collectorName;
    public $customerName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'loan_id', 'collector_id', 'status'], 'integer'],
            [['payment_date', 'created_at', 'updated_at', 'collectorName', 'customerName'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'loan_id' => $this->loan_id,
            'collector_id' => $this->collector_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'payment_date', $this->payment_date]);

        return $dataProvider;
    }

    public function searchDashBoard($params)
    {
        $query = Payment::find();

        $query->joinWith(['collector', 'loan']);
        $query->innerJoin('customer', 'customer.id = loan.customer_id ');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'loan_id',
                'bl',
                'agency_id' => [
                    'asc' => [
                        'agency.name' => SORT_ASC,
                    ],
                    'desc' => [
                        'agency.name' => SORT_DESC,
                    ],
                    'label' => 'Cliente',
                    'default' => SORT_ASC
                ],
                'delivery_date',
                'country_id'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'loan_id' => $this->loan_id,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        if(Yii::$app->authManager->getAssignment('admin',Yii::$app->user->getId()) ||
            Yii::$app->authManager->getAssignment('Administrador',Yii::$app->user->getId()))
        {
            $query->andFilterWhere(['like', 'user.username', $this->collectorName]);
        }
        else
        {
            $query->andFilterWhere([ 'payment.collector_id' => Yii::$app->user->getId()]);
        }

        $query->andFilterWhere(['like', 'payment_date', $this->payment_date]);
        $query->andFilterWhere(['like', 'CONCAT(customer.first_name,customer.last_name)', $this->customerName]);

        return $dataProvider;
    }
}
