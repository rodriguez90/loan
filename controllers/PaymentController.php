<?php

namespace app\controllers;

use app\models\Loan;
use Da\User\Filter\AccessRuleFilter;
use Yii;
use app\models\Payment;
use app\models\PaymentSearch;
use yii\db\conditions\InCondition;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Zxing\FormatException;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'pay' => ['POST'],
                    'pay-bulk' => ['POST'],
                    'list' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRuleFilter::class,
                ],
                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
                    [
                        'actions' => ['index','list','un-paid-list'],
                        'allow' => true,
                        'roles' => ['payment_list'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['payment_view'],
                        'roleParams' => [
                            'payments' => [Yii::$app->request->get('id')]
                        ],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['payment_create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['payment_update'],
                        'roleParams' => [
                            'payments' => [Yii::$app->request->post('id')]
                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['payment_delete'],
                        'roleParams' => [
                            'payments' => [Yii::$app->request->post('id')]
                        ],
                    ],
                    [
                        'actions' => ['pay'],
                        'allow' => true,
                        'roles' => ['payment','payment_update'],
                        'roleParams' => [
                            'payments' => [Yii::$app->request->post('id')]
                        ],
                    ],
                    [
                        'actions' => ['pay-bulk'],
                        'allow' => true,
                        'roles' => ['payment','payment_update'],
                        'roleParams' => [
                            'payments' => Yii::$app->request->post('payments')
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->searchDashBoard(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
//        throw new ForbiddenHttpException('No tiene acceso a esta acción.');
        $model = new Payment();

        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();
            $data["Payment"]['collector_id'] = Yii::$app->user->identity->getId();
            $data["Payment"]['payment_date'] = date('Y-m-d', strtotime($data["Payment"]['payment_date']));

            if($model->load($data))
            {
                $transaction = Yii::$app->getDb()->beginTransaction();

                if ($model->save()) {
                    $payments = Payment::find()
                        ->where(['loan_id'=>$model->loan_id, 'status'=>0])
                        ->orderBy(['id'=>SORT_ASC])
                        ->all();
                    $amount  = (float)$model->amount;
                    foreach ($payments as $payment)
                    {
                        if($amount == 0) break;

                        $paymentAmount = (float)$payment->amount;
                        $paymentAmount -=  $amount;

                        if($paymentAmount <= 0) // la cantidad a descontar era mayor que la cuota
                        {
                            $amount = abs($paymentAmount);
                            $payment->amount = number_format(0,2);
                            $payment->status = Payment::COLLECTED;
                        }
                        elseif ($paymentAmount > 0) // la cantidad a descontar era menor que la cuota
                        {
                            $amount = 0;
                            $payment->amount = number_format($paymentAmount,2);
                        }

                        if(!$payment->save())
                        {
                            $transaction->rollBack();
                            break;
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->status == Payment::COLLECTED)
        {
            throw new ForbiddenHttpException('Esta cuota ya fue cobrada y no debe ser modificada');
        }

        if($data = Yii::$app->request->isPost)
        {
            $flag = true;
            $modelOld = $this->findModel($id);
            $data = Yii::$app->request->post();
            $data["Payment"]['payment_date'] = date('Y-m-d', strtotime($data["Payment"]['payment_date']));
            $data["Payment"]['updated_at'] = date('Y-m-d');

            // validar que si es la ultima cuota debe pagar todo el monto
            // si el pago es

            if($model->load($data))
            {
                $transaction = Payment::getDb()->beginTransaction();

                if ($model->save()) {

                    $payments = Payment::find()
                        ->where(['loan_id'=>$model->loan_id, 'status'=>0])
                        ->andWhere(['<>','id',$model->id])
                        ->orderBy(['payment_date'=>SORT_ASC])
                        ->all();

                    $amountPaid = (float)$model->amount;
                    $amountOld= (float)$modelOld->amount;
                    $amount  = abs($amountOld - $amountPaid);

                    if($amountOld  > $amountPaid) // la cantidad pagada es menor que la cuota fija
                    {
                        if(count($payments) > 0)
                        {
                            $payment = $payments[0];
                            $payment->amount += $amount;

                            if(!$payment->save())
                            {
                                $flag = false;
                                $model->addError('amount', 'Ha ocurrido un error al actualziar la cuota.');
                            }
                        }
                        else
                        {
                            $flag = false;
                            $model->addError('amount', 'Debe pagar la cuota completa.');
                        }
                    }
                    elseif ($amountOld  < $amountPaid)// la cantidad a pagada es mayor que lo que se debia pagar en la cuota
                    {
                        foreach ($payments as $payment)
                        {
                            if($amount == 0) break;

                            $paymentAmount = (float)$payment->amount;
                            $paymentAmount -=  $amount;

                            if($paymentAmount <= 0) // la cantidad a descontar era mayor que la cuota
                            {
                                $amount = abs($paymentAmount);
                                $payment->amount = number_format(0,2);
                                $payment->status = Payment::COLLECTED;
                            }
                            elseif ($paymentAmount > 0) // la cantidad a descontar era menor que la cuota
                            {
                                $amount = 0;
                                $payment->amount = number_format($paymentAmount,2);
                            }

                            if(!$payment->save())
                            {
                                $flag = false;
                                $model->addError('amount', 'Ha ocurrido un error al actualziar la cuota.');
                                break;
                            }
                        }
                    }

                    if($flag)
                    {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    else $transaction->rollBack();
                }
                else $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPay()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->post();

        $response = array();
        $response['success'] = true;
        $response['url'] = Url::to(['/site/index']);
        $response['msg'] = 'La cuota fue registrada con éxito.';
        $response['msg_dev'] = '';

        try
        {
            $id = $params['id'];
            $payment = Payment::findOne($id);
            if($payment)
            {
                $transaction = Yii::$app->getDb()->beginTransaction();
                $payment->status = 1;
                if(!$payment->save())
                {
                    $response['success'] = false;
                    $response['msg'] = 'Ah ocurrido un error al registrar el pago.';
                }

                if($response['success'] == true) $transaction->commit(); else $transaction->rollBack();
            }
            else
            {
                $response['success'] = false;
                $response['msg'] = 'El pago no existe.';
            }
        }
        catch ( Exception $e)
        {
            $response['success'] = false;
            $response['msg'] = 'Ah ocurrido un error al registrar el pago..';
        }

        return $response;
    }

    public function actionPayBulk()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $paymentsId = Yii::$app->request->post('payments');


        $response = array();
        $response['success'] = true;
        $response['url'] = Url::to(['/site/index']);
        $response['msg'] = 'Las cuotas fueron registradas con éxito.';
        $response['msg_dev'] = '';

        try
        {
            $payments = Payment::find()->where(['in', 'id', $paymentsId])->all();

            if(count($payments) > 0)
            {
                $transaction = Yii::$app->getDb()->beginTransaction();
               foreach ($payments as $payment)
               {
                   $payment->status = 1;
                   if(!$payment->save())
                   {
                       $response['success'] = false;
                       $response['msg'] = 'Ah ocurrido un error al registrar las cuotas';
                       $response['msg_dev'] = $payment->getErrorSummary(true);
                       $transaction->rollBack();
                       break;
                   }
               }
                if($response['success'])
                    $transaction->commit();
            }
            else
            {
                $response['success'] = false;
                $response['msg'] = 'Debe enviar las cuotas a registrar.';
            }

//            $result = Payment::updateAll(['status' => 1],new InCondition('id', 'IN', $payments));// Non register un modelhistory
//            if($result <= 0)
//                throw new NotFoundHttpException('El pago no existe.');
        }
        catch ( Exception $e)
        {
            $response['success'] = false;
            $response['msg'] = 'Ah ocurrido un error al registrar las cuotas';
            $response['msg_dev'] = $e->getMessage();
        }

        return $response;
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->get();

        $response = array();
        $response['success'] = true;
        $response['data'] = [];
        $response['msg'] = '';
        $response['msg_dev'] = '';
        if($response['success'])
        {
            try{

                $searchModel = new PaymentSearch();
                $response['data'] = $searchModel->search2($params);
            }
            catch ( Exception $e)
            {
                $response['success'] = false;
                $response['msg'] = "Ah ocurrido al recuperar los pagos.";
                $response['msg_dev'] = $e->getMessage();
                $response['data'] = [];
            }
        }

        return $response;
    }
}
