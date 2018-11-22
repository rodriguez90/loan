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
				<?= Html::a('Nuevo Cuota', ['create'], ['class' => 'btn btn-success']) ?>
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

<?php $this->registerJsFile('@web/js/payment/index.js', ['depends' => ['app\assets\DataTableAsset']]) ?>