<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Reporte';
?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Reporte</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <?php $form = ActiveForm::begin([
                            'action' => ['report'],
                            'method' => 'get',
                        ]); ?>

                        <?php
                        $addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;
                        echo '<div class="form-group drp-container">';
                        echo '<label>Plazo del Préstamo</label>';
                        echo \kartik\daterange\DateRangePicker::widget([
//                                'model'=>$model,
//                                'attribute' => 'dateRange',
                                'name' => 'kvdate2',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'disabled'=>true,
                                'readonly'=>true,
//                               'value'=> '14-11-2018 - 14-01-2019',
//                        'value'=> $defaultValueDateRange,
                                'language' => Yii::$app->language,
                                'hideInput' => true,
//                        'startAttribute' => 'Loan[start_date]',
//                        'endAttribute' => 'Loan[end_date]',
                                'pluginOptions'=>[
                                    'locale'=>
                                        ['format' => 'd-m-Y'],
                                    'separator' => '-',
                                    'opens' => 'left',
                                    'showDropdowns'=>true
                                ]
                            ]) . $addon;
                        echo '</div>';
                        ?>
                        <div class="form-group">
                            <?php echo Html::checkboxList(
                                'options',
                                null,
                                ['Clientes en mora',
                                'Total del clientes',
                                'Cantidad Prestada',
                                'Cantidad por cobrar',
                                'Ganancias']
                            )?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('Limpiar', ['class' => 'btn btn-default']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

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
                            <th>Acciones</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
