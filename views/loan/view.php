<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */

//$this->title = 'Préstamo: No.  ' . $model->id;
$this->title = 'Datos del Préstamo';
//$this->title = null;
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6 col-xs-6 col-md-offset-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
                    <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Refinanciar', ['refinance', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Está seguro que desea eliminar este préstamo?',
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
                        [
                            'attribute' => 'customer.first_name',
                            'label' => 'Cliente',
                        ],
                        [
                            'attribute' => 'collector.username',
                            'label' => 'Cobrador',
                        ],
                        [
                            'attribute' => 'porcent_interest',
                            'value' => strval($model->porcent_interest) . "%",
                        ],
                        [
                            'attribute'=> 'amount',
//                            'value'=>number_format($model->amount,2)
                            'format' => 'currency',
                        ],
                        [
                            'attribute'=> 'fee_payment',
//                            'value'=>number_format($model->fee_payment,2)
                            'format' => 'currency',
                        ],
                        [
                            'label'=>'Cantidad Cancelada',
                            'value' => strval($model->getAmountPaid()),
                            'format' => 'currency',
                        ],
                        [
                            'attribute' => 'status',
                            'value' => \app\models\Loan::STATUS_LABEL[$model->status]
                        ],
                        [
                            'attribute' => 'refinancing_id',
//                            'value' => empty($model->refinancing_id): 'No ',
                        ],
                        [
                            'label' => 'Frecuencia de Pago(días)',
                            'attribute' => 'frequency_payment',
                        ],
                        [
                            'attribute' => 'banker.username',
                            'label' => 'Prestamista',
                        ],
                        'start_date:date',
                        'end_date:date',
                        'created_at:datetime',
//                        'updated_at:datetime',
                    ],
                    'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view']
                ]) ?>
            </div>
        </div>
</div>
