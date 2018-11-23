<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
                <div class="box-header with-border">
                    <?= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success btn']) ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="display table table-bordered  table-striped table-condensed no-wrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <!--                            <th></th>-->
                                <th class="all">Cliente</th>
                                <th class="all">Cedúla</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

<?php $this->registerJsFile('@web/js/customer/index.js', ['depends' => ['app\assets\DataTableAsset']]) ?>