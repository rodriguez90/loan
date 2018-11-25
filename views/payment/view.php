<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

//$this->title = 'Cobro: No.  ' . $model->id;
$this->title = 'Datos de la Cuota';
$this->params['breadcrumbs'][] = ['label' => 'Cuotas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6 col-xs-6 col-md-offset-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="pull-right">
                    <?php  echo Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?php echo Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Está seguro que desea eliminar este Cuota?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'loan_id',
                    'collector.username',
                    'payment_date:date',
                    'amount:currency',
                    'collectorName',
                    'customerName',
                    [
                        'label'=>'Estado',
                        'attribute'=>'status',
                        'format'=>'raw',
                        'value' => function ($data) {
                            return $data['status'] == 1?
                                '<span class="label label-success pull-left">Cobrado</span>' :
                                '<span class="label label-danger">Pendiente</span>';
                        },
                    ],
                ],
                'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view']
            ]) ?>
        </div>
    </div>
</div>
