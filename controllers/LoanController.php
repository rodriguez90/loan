<?php

namespace app\controllers;

use app\models\Payment;
use Da\User\Filter\AccessRuleFilter;
use Da\User\Model\User;
use Yii;
use app\models\Loan;
use app\models\LoanSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * LoanController implements the CRUD actions for Loan model.
 */
class LoanController extends Controller
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
//                    'refinance' => ['POST'],
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
                        'actions' => ['index', 'user-list', 'loan-list', 'list'],
                        'allow' => true,
                        'roles' => ['loan_list'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['loan_view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['loan_create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['loan_update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['loan_delete'],
                    ],
                    [
                        'actions' => ['refinance'],
                        'allow' => true,
                        'roles' => ['refinance'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Loan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LoanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Loan model.
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
     * Creates a new Loan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Loan();

        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            $data["Loan"]['banker_id'] = Yii::$app->user->identity->getId();
            $data["Loan"]['status'] = Loan::ACTIVE;

            $data["Loan"]['start_date'] = date('Y-m-d', strtotime($data["Loan"]['start_date']));
            $data["Loan"]['end_date'] = date('Y-m-d', strtotime($data["Loan"]['end_date']));

            $payments = json_decode($data['payments']);

            $result = true;

            if ($model->load($data))
            {
                $transaction = Yii::$app->getDb()->beginTransaction();
                if($model->save())
                {
                    foreach ($payments as $payment)
                    {
                        $pay = new Payment();
                        $pay->loan_id = $model->id;
                        $pay->payment_date = date('Y-m-d', strtotime($payment->payment_date));
                        $pay->collector_id = $model->collector_id;
                        $pay->amount = $model->fee_payment;

                        if(!$pay->save())
                        {
                            $result = false;
                            $model->addError(nul, 'Ah ocurrido un error al generar los pagos.');
                            break;
                        }
                    }
                }
                else
                    $result = false;

                if($result)
                {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else
                    $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Loan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelOld = $this->findModel($id);

        if(Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            $data["Loan"]['banker_id'] = Yii::$app->user->identity->getId();
            $data["Loan"]['updated_at'] = date('Y-m-d');

            if ($model->load($data))
            {
                $transaction = Yii::$app->getDb()->beginTransaction();
                $modelOld->customer_id = $model->customer_id;
                $modelOld->collector_id = $model->collector_id;
                $modelOld->updated_at = $model->updated_at;
                if($modelOld->update(true,['customer_id', 'collector_id', 'updated_at', 'banker_id']) !== false)
                {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $modelOld->id]);
                }
                else
                    $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $modelOld,
        ]);
    }

    public function actionRefinance($id)
    {
        $model = $this->findModel($id);

        if($model->status == 0 || $model->status == 2)
        {
            throw new BadRequestHttpException('Este préstamo no puede ser refinanciado.');
        }

        if(Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();
            $data["Loan"]['banker_id'] = Yii::$app->user->identity->getId();
            $data["Loan"]['status'] = Loan::ACTIVE;
            $data["Loan"]['start_date'] = date('Y-m-d', strtotime($data["Loan"]['start_date']));
            $data["Loan"]['end_date'] = date('Y-m-d', strtotime($data["Loan"]['end_date']));

            $payments = json_decode($data['payments']);

            $result = true;

            $newModel = new Loan();

            if ($newModel->load($data))
            {
                $transaction = Yii::$app->getDb()->beginTransaction();

                $newModel->amount = $model->amount + $model->getAmountUnPaid();

                if($newModel->save())
                {
                    foreach ($payments as $payment)
                    {
                        $pay = new Payment();
                        $pay->loan_id = $newModel->id;
                        $pay->payment_date = date('Y-m-d', strtotime($payment->payment_date));
                        $pay->collector_id = $newModel->collector_id;
                        $pay->amount = $newModel->fee_payment;
                        $pay->status = Payment::PENDING;

                        if(!$pay->save())
                        {
                            $result = false;
                            $model->addError(nul, 'Ah ocurrido un error al generar las cuotas del préstamo.');
                            break;
                        }
                    }

                    if($result)
                    {
                        $model->refinancing_id = $newModel->id;
                        $model->status = Loan::INACTIVE;
                        if(!$model->save())$result = false;
                    }
                }
                else
                    $result = false;

                if($result)
                {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $newModel->id]);
                }
                else
                    $transaction->rollBack();
            }
        }

        return $this->render('refinance', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Loan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
//        $params = Yii::$app->request->get();
        $response = array();
        $response['success'] = true;
        $response['data'] = [];
        $response['msg'] = 'Préstamo eliminado con éxito';
        $response['msg_dev'] = '';

        $loan = $this->findModel($id);
        try
        {
            if($loan)
            {
                if(!$loan->delete())
                {
                    $response['success'] = true;
                    $response['msg'] = 'Ha ocurrido un error al eliminar el préstamo.';
                }
            }
            else
            {
                $response['success'] = true;
                $response['msg'] = 'El préstamo no existe.';
            }

        }
        catch (\Exception $e)
        {
            $response['success'] = true;
            $response['msg'] = 'Ha ocurrido un error al eliminar el préstamo.';
        }

        return $response;
    }


    /**
 * @param null $q
 * @param null $id
 * @return array
 */
    public function actionLoanList($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results'=>['id'=>'', 'text'=>'']];

        if(!is_null($q))
        {
            $query = new Query();
            $query->select(['id',"id AS text"])
                ->from('loan')
                ->andWhere(['like', 'CONVERT(id, CHAR(50))', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0)
        {
            $loan = Loan::findOne($id);
            $out['results']=['id'=>$id, 'text'=> $loan->id];
        }

        return $out;
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

        if(!is_null($params))
        {
            try
            {
                $search = new LoanSearch();
                $response['data'] = $search->search2($params);
            }
            catch ( Exception $e)
            {
                $response['success'] = false;
                $response['msg'] = "Ah ocurrido al recuperar los préstamos.";
                $response['msg_dev'] = $e->getMessage();
                $response['data'] = [];
            }
        }

        return $response;
    }

    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionUserList($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results'=>['id'=>'', 'text'=>'']];

        if(!is_null($q))
        {
            $data = User::find()
                ->select(['user.id',"user.username"])
                ->leftJoin('profile', 'profile.user_id=user.id')
                ->andWhere(['like', 'user.username', $q])
                ->orWhere(['like','profile.name', $q])
                ->all();

            $result = [];

            foreach ($data as $user)
            {
                if(Yii::$app->authManager->getAssignment('Cobrador', $user->id))
                {
                    $result[] = ['id' => $user->id, 'username' => $user->username];
                }
            }
            $out['results'] = array_values($result);
        }
        elseif ($id > 0)
        {
            $user = User::findOne($id);
            $out['results']=['id'=>$id, 'text'=> $user->username];
        }

        return $out;
    }

    /**
     * Finds the Loan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Loan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Loan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionComputeLoan()
    {
        // FIXME: this is for compute loan by ajax
    }
}
