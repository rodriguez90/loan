<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use yii\db\Query;
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
