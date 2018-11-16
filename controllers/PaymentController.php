<?php

namespace app\controllers;

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
                        'actions' => ['index','view','list','pay','pay-bulk'],
                        'allow' => true,
                        'roles' => ['admin','Administrador','Cobrador'],
//                        'permissions' => [],
                    ],
                    [
                        'actions' => ['create','update','delete'],
                        'allow' => true,
                        'roles' => ['admin','Administrador',],
//                        'permissions' => [],
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
        $model = new Payment();

        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();
            $data["Payment"]['collector_id'] = Yii::$app->user->identity->getId();

            if ($model->load($data) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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

        $data["Payment"]['updated_at'] = date('Y-m-d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPay($id)
    {

        try{
            $payment = Payment::findOne($id);
            if($payment)
            {
                $payment->status = 1;
                if(!$payment->save())
                    throw new NotFoundHttpException('Ah ocurrido un error al registrar el pago.');
            }
            else
                throw new NotFoundHttpException('El pago no existe.');
//            $result = Payment::updateAll(['status' => 1],new InCondition('id', 'IN', [$id])); // Non register un modelhistory
//            if($result <= 0)
//                throw new NotFoundHttpException('El pago no existe.');
        }
        catch ( Exception $e)
        {
            throw new NotFoundHttpException('Ah ocurrido un error al registrar el pago.');
        }

        return $this->redirect(Url::to(['/site/index']));
    }

    public function actionPayBulk()
    {
        $paymentsId = Yii::$app->request->post('payments');

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
                       $transaction->rollBack();
                       throw new NotFoundHttpException('Ah ocurrido un error al registrar el pago.');
                   }
               }
                $transaction->commit();
            }
            else
            {
                throw new NotFoundHttpException('Los pagos no existen.');
            }

//            $result = Payment::updateAll(['status' => 1],new InCondition('id', 'IN', $payments));// Non register un modelhistory
//            if($result <= 0)
//                throw new NotFoundHttpException('El pago no existe.');
        }
        catch ( Exception $e)
        {
            throw new NotFoundHttpException('Ah ocurrido un error al registrar los pagos.');
        }

        return $this->redirect(Url::to(['/site/index']));
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

        $response = array();
        $response['success'] = true;
        $response['data'] = [];
        $response['msg'] = '';
        $response['msg_dev'] = '';
        if($response['success'])
        {
            try{

                $searchModel = new PaymentSearch();
                $response['data'] = $searchModel->searchDashBoard2();
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
