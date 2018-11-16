<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'Inicio';
?>
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $loanCount ?></h3>

                <p>Préstamos</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href= <?php echo \yii\helpers\Url::toRoute(['/loan/create'])?> class="small-box-footer">Nuevo Prestamo <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $paymentCount ?></h3>
<!--                <h3>4<sup style="font-size: 20px">%</sup></h3>-->

                <p>Cuotas Pagadas</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href= <?php echo \yii\helpers\Url::toRoute(['/payment/create'])?> class="small-box-footer">Registrar Cobro <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= $customerCount ?></h3>

                <p>Clientes</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href= <?php echo \yii\helpers\Url::toRoute(['/customer/create'])?> class="small-box-footer">Registrar Cliente <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= $unpaidCount ?></h3>

                <p>Cuotas por Pagar</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">Reportes de Impagos <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Cuotas Pendientes</h3>

                <div class="box-tools pull-right">
                    <button id="pay_btn" type="button" class="btn btn-primary btn-xs btn-box-too">Pagar Seleccionados</button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <div class="table-responsive">
                    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
//                    'id'=>'payments',
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
                        'amount',
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

                        [
                            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                            'attribute' => 'status',
                            'content' => function ($data) {
                                return $data['status'] ? '<span class="label label-success pull-left">Cobrado</span>' : '<span class="label label-danger">Pendiente</span>';
                            },
                            'filter' => ['0' =>'Pendiente', '1' =>'Cobrado',],
                        ],
                        [
//                           'name'=>'id',
//                           'header' => Html::checkbox('select_all', false, [
//                                'id'=>'select_all',
//                                'class' => 'select-on-check-all pull-right',
//                                'label' => '<span class="pull-left">Seleccionar</span>'
//                           ]),
                           'class' => '\yii\grid\CheckboxColumn',
                            'header'=> 'Pagar',
//                            'id',
//                            'multiple' => true
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                          'controller'=>'payment'
                        ],
                    ],
                    'tableOptions'=>['class'=>'table table-striped table-bordered table-condensed' ]
                ]); ?>
                </div>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

<?php $this->registerJsFile('@web/js/site/index.js', ['depends' => ['app\assets\AppAsset']]) ?>