<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cobros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12 col-xs-6">
        <div class="box box-solid">

            <div class="box-header with-border">
                <?= Html::a('Nuevo Cobro', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
            <div class="box-body">

                <?php Pjax::begin(); ?>
                <div class="table-responsive">
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'loan_id',
                                'content' => function ($data) {
                                    return  Html::a($data['loan_id'],
                                        \yii\helpers\Url::toRoute(['/loan/view/', 'id' => $data['loan_id']]));
                                }
                            ],
                            [
                                'attribute'=>'customerName',
                            ],
                            [
                                'attribute'=>'collectorName',
                            ],
                            [
                                'attribute' => 'payment_date',
                                'value' => 'payment_date',
//                            'format' => 'php:date',
                                'filter' =>  \kartik\date\DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute'=>'payment_date',
                                    'pluginOptions' => [
//                                    'format' => 'dd-M-yyyy',
                                        'format' => 'yyyy-m-dd',
                                        'autoclose'=>true,
                                        'todayHighlight' => true
                                    ]
                                ]),
                                'format' => 'html',
                            ],
                            'amount',
                            [
                                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                                'attribute' => 'status',
                                'content' => function ($data) {
                                    return $data['status'] ? '<span class="label label-success pull-left">Cobrado</span>' : '<span class="label label-danger">Pendiente</span>';
                                },
                                'filter' => ['0' =>'Pendiente', '1' =>'Cobrado',],
                            ],

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
