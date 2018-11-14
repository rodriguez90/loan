<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Préstamos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <?= Html::a('Nuevo Préstamo', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
            <div class="box-body">
                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
//                        ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            'customer.first_name',
//                        'banker',
                            'porcent_interest',
                            'amount',
                            'fee_payment',
                            [
                                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                                'attribute' => 'status',
//                            'value' => function($data) {
//                                return \app\models\Loan::STATUS_LABEL[$data['status']];
//                            },
                                'content' => function ($data) {
                                    return $data['status'] ? '<span class="label label-success pull-left">Activo</span>' : '<span class="label label-danger">Inactivo</span>';
                                },
                                'filter' => ['0' =>'Inactivo', '1' =>'Activo' , '2'=>'Cerrado',],
                            ],
                            //'refinancing_id',
//                        'frequency_payment',
                            [
                                'attribute' => 'start_date',
//                            'value' => function($data){
////                    var_dump($data['start_date']);die;
//                                return  date('d-m-Y ', $data['start_date']);
//                            },
                                'format' => 'date',
                                'filter' =>  \kartik\date\DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute'=>'start_date',
                                    'pluginOptions' => [
                                        'format' => 'yyyy-m-dd',
                                        'autoclose'=>true,
                                        'todayHighlight' => true
                                    ]
                                ]),
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'end_date',
                                'value' => 'end_date',
//                            'format' => 'php:date',
                                'filter' =>  \kartik\date\DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute'=>'end_date',
                                    'pluginOptions' => [
//                                    'format' => 'dd-M-yyyy',
                                        'format' => 'yyyy-m-dd',
                                        'autoclose'=>true,
                                        'todayHighlight' => true
                                    ]
                                ]),
                                'format' => 'html',
                            ],

//                        'end_date:date',
                            //'created_at',
                            //'updated_at',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                        'tableOptions'=>['class'=>'table table-striped table-bordered table-condensed' ]
                    ]); ?>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
