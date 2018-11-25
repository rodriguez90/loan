<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Loan;

/**
 * LoanSearch represents the model behind the search form of `app\models\Loan`.
 */
class LoanSearch extends Loan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'banker_id', 'status', 'refinancing_id', 'frequency_payment', 'collector_id'], 'integer'],
            [['amount', 'porcent_interest', 'fee_payment'], 'number'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
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
        $query = Loan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'customer_id' => $this->customer_id,
            'banker_id' => $this->banker_id,
            'amount' => $this->amount,
            'porcent_interest' => $this->porcent_interest,
            'status' => $this->status,
            'refinancing_id' => $this->refinancing_id,
            'frequency_payment' => $this->frequency_payment,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fee_payment' => $this->fee_payment,
            'collector_id' => $this->collector_id,
        ]);

        return $dataProvider;
    }

    public function search2($params)
    {
        $this->load($params);

        $query = Loan::find();
        $query->joinWith(['collector', 'customer']);
//        $query->innerJoin('customer', 'customer.id = loan.customer_id ');

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'loan.id' => $this->id,
            'loan.customer_id' => $this->customer_id,
            'banker_id' => $this->banker_id,
            'loan.amount' => $this->amount,
            'porcent_interest' => $this->porcent_interest,
            'loan.status' => $this->status,
            'refinancing_id' => $this->refinancing_id,
            'frequency_payment' => $this->frequency_payment,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'loan.created_at' => $this->created_at,
            'loan.updated_at' => $this->updated_at,
            'fee_payment' => $this->fee_payment,
            'loan.collector_id' => $this->collector_id,
        ]);

        if(Yii::$app->authManager->getAssignment('Cobrador',Yii::$app->user->getId()))
        {
            $query->andFilterWhere(['collector_id'=>Yii::$app->user->getId()]);
        }

        $result = $query->select([
                            'loan.id',
                            'loan.porcent_interest',
                            'loan.amount',
                            'loan.fee_payment',
                            'loan.start_date',
                            'loan.end_date',
                            'loan.frequency_payment',
                            'loan.status',
                            'loan.refinancing_id',
                            'user.username as collectorName',
                            'CONCAT(customer.first_name,customer.last_name) as customerName',
                            'customer.id as customerId',

                        ])
                        ->orderBy(['loan.id'=>SORT_ASC])
                        ->asArray()
                        ->all();
        return $result;
    }
}
