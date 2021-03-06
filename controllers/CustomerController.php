<?php

namespace app\controllers;

use Da\User\Filter\AccessRuleFilter;
use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                        'actions' => ['index', 'customer-list', 'list'],
                        'allow' => true,
                        'roles' => ['customer_list'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['customer_view'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['customer_create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['customer_update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [ 'customer_delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();
        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            $data['Customer']['created_by'] = Yii::$app->user->identity->getId();

            if ( $model->load($data) && $model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            $data['Customer']['created_by'] = Yii::$app->user->identity->getId();
            $data["Customer"]['updated_at'] = date('Y-m-d');

            if ( $model->load($data) && $model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $this->findModel($id)->delete();

        if(Yii::$app->request->isAjax)
        {

            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = array();
            $response['success'] = true;
            $response['msg'] = 'Cliente eliminado con éxito';
            $response['msg_dev'] = '';
            return $response;
        }

        return $this->redirect(['index']);
    }

    public function actionCustomerList($q = null, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results'=>['id'=>'', 'text'=>'']];

        if(!is_null($q))
        {
            $query = new Query();
            $query->select(['id',"CONCAT(first_name, ' ', last_name) AS text"])
                  ->from('customer')
                  ->where(['active'=>1])
                  ->andWhere(['like', 'last_name', $q])
                  ->orWhere(['like', 'dni', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0)
        {
            $customer = Customer::findOne($id);
            $out['results']=['id'=>$id, 'text'=> $customer->first_name . ' ' . $customer->last_name];
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
                $search = new CustomerSearch();
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
