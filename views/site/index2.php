<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = '';
?>
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

<!--<div class="row">-->
<!--    <div class="col-lg-12 col-xs-12">-->
<?php // echo $this->render('../payment/_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsive'=>true,
            'hover'=>true,
            'striped' => true,
            'pjax'=>true,
            'pjaxSettings'=>[
                'neverTimeout'=>true,
            ],
            'resizableColumns'=>true,
            'hideResizeMobile'=>true,
            'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
//            'floatHeader'=>true,
//            'floatHeaderOptions'=>['scrollingTop'=>'50'],

//            'toolbar' => [
////                        [
////                            'content'=>
////                                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
////                                    'type'=>'button',
////                                    'title'=>'Add Book',
////                                    'class'=>'btn btn-success'
////                                ]) . ' '.
////                                Html::a('<i class="fas fa-redo"></i>', ['grid-demo'], [
////                                    'class' => 'btn btn-secondary',
////                                    'title' =>'Reset Grid'
////                                ]),
////                        ],
////                '{export}',
////                '{toggleData}'
//            ],
//            'toggleDataContainer' => ['class' => 'btn-group-sm'],
//            'exportContainer' => ['class' => 'btn-group-sm'],
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-credit-card"></i> Cuotas Pendientes</h3>',
                'type'=>'default',
//                            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create Country', ['create'], ['class' => 'btn btn-success']),
//                            'after'=>Html::a('<i class="fas fa-redo"></i> Refrescar', ['index'], ['class' => 'btn btn-info']),
                'footer'=>false,
            ],
            'panelTemplate'=>'
                {panelHeading}
                {panelBefore}
                {items}                                
                {panelAfter}
                {pager}',
            'columns' => [
//                        ['class' => 'yii\grid\SerialColumn'],
//                        [
//                            'attribute' => 'loan_id',
//                            'content' => function ($data) {
//                                return  Html::a($data['loan_id'],
//                                    \yii\helpers\Url::toRoute(['/loan/view/', 'id' => $data['loan_id']]));
//                            }
//                        ],
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
                        return $data['status'] ? '<span class="label label-success">Cobrado</span>' : '<span class="label label-danger">Pendiente</span>';
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
                    'class' => \kartik\grid\CheckboxColumn::className(),
//                    'noWrap'=>false,
                    'mergeHeader'=>false,
//                    'header'=> 'Pagar',
//                            'id',
//                            'multiple' => true
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'controller'=>'payment'
                ],
            ],
//            'tableOptions'=>['class'=>'table table-striped table-bordered table-condensed' ]
        ]); ?>
<!--    </div>-->
<!--</div>-->

<?php $this->registerJsFile('@web/js/site/index.js', ['depends' => ['app\assets\AppAsset']]) ?>