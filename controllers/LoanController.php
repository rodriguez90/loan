<?php

namespace app\controllers;

use Da\User\Filter\AccessRuleFilter;
use Da\User\Model\User;
use Yii;
use app\models\Loan;
use app\models\LoanSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
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
                        'actions' => ['index','view', 'user-list'],
                        'allow' => true,
                        'roles' => ['admin','Administrador','Cobrador'],
//                        'permissions' => ['customer_view', 'customer_list'],
                    ],
                    [
                        'actions' => ['create','update','delete'],
                        'allow' => true,
                        'roles' => ['admin','Administrador'],
//                        'permissions' => ['customer_create', 'customer_update', 'customer_delete'],
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

            if ($model->load($data) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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

        if($data = Yii::$app->request->isPost)
        {
            $data = Yii::$app->request->post();

            $data["Loan"]['banker_id'] = Yii::$app->user->identity->getId();
            $data["Loan"]['status'] = Loan::ACTIVE;

            $data["Loan"]['start_date'] = date('Y-m-d', strtotime($data["Loan"]['start_date']));
            $data["Loan"]['end_date'] = date('Y-m-d', strtotime($data["Loan"]['end_date']));
            $data["Loan"]['updated_at'] = date('Y-m-d');

            if ($model->load($data) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        return $this->render('update', [
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            $out['results'] = array_values($data);
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
}
