<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cuotas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
<!--                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>-->
<!--                    </button>-->
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table id="data-table" class="display table table-bordered no-wrap" width="100%">
                        <thead>
                        <tr>
                            <th class="all">Cliente</th>
                            <th class="all">Cuota</th>
                            <th class="all">Fecha de Pago</th>
                            <th>Cédula</th>
                            <th>Cobrador</th>
                            <th>Estado</th>
                            <th><input type="checkbox" name="select_all" value="1" id="select-all"></th>
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

<?php $this->registerJsFile('@web/js/payment/index.js', ['depends' => ['app\assets\DataTableAsset']]) ?>