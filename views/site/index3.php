<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = '';
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
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $unpaidCount ?></h3>

                    <p>Cuotas por Pagar</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">Reporte <i class="fa fa-arrow-circle-right"></i></a>
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
    </div>

    <div class="row">

        <div class="col-lg-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Cuotas Pendientes</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="display table table-bordered no-wrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><input type="checkbox"></th>
                                <th class="all">Cliente</th>
                                <th class="all">Fecha de Pago</th>
                                <th>Cuota</th>
                                <th>Cédula</th>
                                <th>Cobrador</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

<?php $this->registerJsFile('@web/js/site/index.js', ['depends' => ['app\assets\DataTableAsset']]) ?>