<?php

namespace app\controllers;

use app\models\Customer;
use app\models\Loan;
use app\models\Payment;
use app\models\PaymentSearch;
use Da\User\Filter\AccessRuleFilter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRuleFilter::class,
                ],
//                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['report'],
                        'allow' => true,
                        'roles' => ['report_view'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
//        if (\Yii::$app->user->can('createPost')) {
//            // create post
//        }

        $customerCount = Customer::find()->count();
        $loanCount = Loan::find()->count();
        $paymentCount = Payment::find()
            ->innerJoin('loan', 'loan.id=payment.loan_id')
            ->where(['payment.status'=>1, 'loan.status'=>1])->count();
        $unpaidCount = Payment::find()
            ->innerJoin('loan', 'loan.id=payment.loan_id')
            ->where(['payment.status'=>0, 'loan.status'=>1])->count();

        return $this->render('index3', [
            'customerCount' =>$customerCount,
            'loanCount' =>$loanCount,
            'paymentCount' =>$paymentCount,
            'unpaidCount' =>$unpaidCount,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionReport()
    {
        if(Yii::$app->request->isGet && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $params = Yii::$app->request->get();

//            var_dump($params);die;

            $response = array();
            $response['success'] = true;
            $response['data'] = [];
            $response['msg'] = '';
            $response['msg_dev'] = '';

            $startDate =  strtotime($params['start_date']);
            $endDate = strtotime($params['end_date']);
            $option = $params['option'];


            $select = [];
            $groupBy = [];
            $having = [];

            $query = Loan::find();


            if(isset($option) && $option == 'customerUnPaid')
            {
                $select []= "concat(customer.first_name,' ', customer.last_name) as customerName";
                $select []= "customer.dni";
                $select []= "sum(payment.amount) as amount";
                $select []= "payment_date";

                $query->innerJoin('customer', 'customer.id = loan.customer_id');
                $query->innerJoin('payment', 'payment.loan_id = loan.id');

                $query->andWhere(['loan.status'=>Loan::ACTIVE]);
                $query->andFilterWhere(['payment.status'=>Payment::PENDING]);
                $query->andFilterWhere(['<','payment.payment_date',date('Y-m-d')]);

                if($startDate != false and $endDate != false) // period
                {
                    $query->andFilterWhere([
                        'BETWEEN',
                        'payment.payment_date',
                        date('Y-m-d', $startDate),
                        date('Y-m-d', $endDate)]);
                }

                $groupBy []= 'loan.id';
                $groupBy []= 'payment.id';

            }

            if(isset($option) && $option == 'loanAmount')
            {
                $select[] = "sum(loan.amount) as loanAmount";

//                $groupBy []= 'loan.id';

//                $query->innerJoin('customer', 'customer.id = loan.customer_id');

                $query->andWhere(['loan.status'=>Loan::ACTIVE])
                    ->orWhere(['loan.status'=>Loan::CLOSE]);

                if($startDate != false and $endDate != false) // period
                {
                    $query->andFilterWhere([
                        'BETWEEN',
                        'loan.start_date',
                        date('Y-m-d', $startDate),
                        date('Y-m-d', $endDate)]);

                    $query->andFilterWhere([
                        'BETWEEN',
                        'loan.end_date',
                        date('Y-m-d', $startDate),
                        date('Y-m-d', $endDate)]);
                }
            }

            if(isset($option) && $option == 'amountPaid')
            {
                $select []= "sum(payment.amount) as amountPaid";

                $query->innerJoin('payment', 'payment.loan_id = loan.id');
                $query->andWhere(['loan.status'=>Loan::ACTIVE]);
                $query->andFilterWhere(['payment.status'=>Payment::PENDING]);

                if($startDate != false and $endDate != false) // period
                {
                    $query->andFilterWhere([
                        'BETWEEN',
                        'payment.payment_date',
                        date('Y-m-d', $startDate),
                        date('Y-m-d', $endDate)]);
                }
            }

            if(isset($option) && $option == 'earnings')
            {
                $select[] = "sum((loan.amount*loan.porcent_interest)/100) as earnings";

                $query->andWhere(['loan.status'=>Loan::ACTIVE])
                ->orWhere(['loan.status'=>Loan::CLOSE]);
            }

            try
            {
                $query->select($select)->groupBy($groupBy)->having($having);
                $command = $query->createCommand();
                $response['data'] = $query
                                    ->asArray()
                                    ->all();

                $response['msg_dev'] = $command->getRawSql();
            }
            catch ( Exception $e)
            {
                $response['success'] = false;
                $response['msg'] = "Ah ocurrido al recuperar los datos.";
                $response['msg_dev'] = $e->getMessage();
                $response['data'] = [];
            }
            return $response;
        }

        return $this->render('report');
    }
}
