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
<!--            <div class="box-header with-border">-->
<!--                <h3 class="box-title"></h3>-->
<!--                <div class="box-tools pull-right">-->
<!--                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['report'],
                    'method' => 'get',
                ]); ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-6">


                        <?php
                        $addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;
                        echo '<div class="form-group drp-container">';
                        echo '<label>Período</label>';
                        echo \kartik\daterange\DateRangePicker::widget([
//                                'model'=>$model,
//                                'attribute' => 'dateRange',
                                'name' => 'kvdate2',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'language' => Yii::$app->language,
                                'hideInput' => true,
                        'startAttribute' => 'start_date',
                        'endAttribute' => 'end_date',
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
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input id="customerUnPaid" name="option" type="radio" value="customerUnPaid">
                                    Clientes en mora
                                </label>
                            </div>

<!--                            <div class="checkbox">-->
<!--                                <label>-->
<!--                                    <input id="totalCustomer" name="totalCustomer" type="radio">-->
<!--                                    Total del clientes                                </label>-->
<!--                            </div>-->

                            <div class="checkbox">
                                <label>
                                    <input id="loanAmount" name="option" type="radio" value="loanAmount">
                                    Cantidad Prestada
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input id="amountPaid" name="option" type="radio" value="amountPaid">
                                    Cantidad por cobrar
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input id="earnings" name="option" type="radio" value="amountPaid">
                                    Ganancias
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::button('Buscar', ['id'=>'searchBtn', 'class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Limpiar', ['id'=>'reset', 'class' => 'btn btn-default']) ?>
                </div>
                <?php ActiveForm::end(); ?>

                <div class="table-responsive">
                    <table id="data-table" class="display table table-bordered no-wrap" width="100%">
                        <thead>
<!--                        <tr>-->
<!--                            <th class="all">Cliente</th>-->
<!--                            <th>Cédula</th>-->
<!--                            <th class="all">Cuota</th>-->
<!--                            <th class="all">Fecha de Pago</th>-->
<!--                            <th>Cobrador</th>-->
<!--                            <th>Estado</th>-->
<!--                        </tr>-->
                        </thead>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->registerJsFile('@web/js/site/report.js', ['depends' => ['app\assets\DataTableAsset']]) ?>

