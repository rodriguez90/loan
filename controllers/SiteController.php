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
                        'allow' => true,
                        'roles' => ['@'],
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
        if(count(Yii::$app->request->get()) > 0 )
        {
            // do report
        }

        return $this->render('report');
    }
}
