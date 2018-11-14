<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Customer;
use Da\User\Model\User;
use kartik\number\NumberControl;
use kartik\daterange\DateRangePicker;

use app\assets\DataTableAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */
/* @var $form yii\widgets\ActiveForm */

DataTableAsset::register($this);

$url = \yii\helpers\Url::to(['/customer/customer-list']);
$urlUser = \yii\helpers\Url::to(['/loan/user-list']);

$customerName = "";
$collectorName = "";

if($model->customer_id)
{
    $customer = Customer::findOne($model->customer_id);
    $customerName = $customer->first_name . ' ' . $customer->last_name;
}

if($model->collector_id)
{
    $collector = User::findOne($model->collector_id);
    $collectorName = $collector->username;
}

$defaultValueDateRange = '';

if(!empty($model->start_date) and !empty($model->end_date ))
{
    $defaultValueDateRange = date('d-m-Y', strtotime($model->start_date)). ' - ' . date('d-m-Y', strtotime($model->end_date));
}

$disOptions = ['class'=>'form-control kv-monospace'];
$saveOptions = [
        'type' => 'text',
        'label' => '<label>Saved Value: </label>',
        'class' => 'kv-saved',
        'readonly' => true,
        'tabindex' => 1000,
];

$saveCont = ['class' => 'kv-saved-cont'];

?>

<?php if ($model->hasErrors()) {
//    var_dump($model->errors);die;
    \Yii::$app->getSession()->setFlash('error', $model->getErrorSummary(true));
}
?>

<!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
        <!-- begin box -->
        <div class="box box-success">
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-4">

                        <?= $form->field($model, 'customer_id')->widget(\kartik\select2\Select2::classname(), [
                                'bsVersion' => '3.x',
                                'initValueText' => $customerName,
                                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                                'options' => ['placeholder' => 'Buscar por apellido o cédula'],
                                'pluginOptions' => [
                                    'allowClear' => false,
//                                    'minimumInputLength' => 3,
                                    'ajax' => [
                                        'url' => $url,
                                        'dateType' => 'json',
                                        'data' => new \yii\web\JsExpression("function(params) {return {q:params.term};}")
                                    ],
                                    'escapeMarkup' => new \yii\web\JsExpression("function(markup) {return markup;}"),
                                    'templateResult' => new \yii\web\JsExpression("function(customer) {return customer.text;}"),
                                    'templateSelection' => new \yii\web\JsExpression("function(customer) {return customer.text;}"),
                                ],
                            ]);?>

                        <?= $form->field($model, 'collector_id')->widget(\kartik\select2\Select2::classname(), [
                            'bsVersion' => '3.x',
                            'initValueText' => $collectorName,
                            'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Buscar por usuario'],
                            'pluginOptions' => [
                                'allowClear' => false,
//                                'minimumInputLength' => 3,
                                'ajax' => [
                                    'url' => $urlUser,
                                    'dateType' => 'json',
                                    'data' => new \yii\web\JsExpression("function(params) {return {q:params.term};}")
                                ],
                                'escapeMarkup' => new \yii\web\JsExpression("function(markup) {return markup;}"),
                                'templateResult' => new \yii\web\JsExpression("function(user) {return user.username;}"),
                                'templateSelection' => new \yii\web\JsExpression("function(user) {return user.hasOwnProperty('username') ? user.username : user.text;}"),
//                                'templateSelection' => new \yii\web\JsExpression("function(user) {return user.text;}"),
                            ],
                        ]);?>

                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'porcent_interest')
                            ->dropDownList([0 => '', 10 => '10%', 15 => '15%', 20 => '20%', 25 => '25%'], [
                                    'data-parsley-required' => "true",
                                    'data-parsley-type' => "integer",
                                    'data-parsley-type' => "number",
                                    'data-parsley-min'=>"10"]
                            )?>

                        <?= $form->field($model, 'amount')->widget(NumberControl::className(), [
                            'maskedInputOptions' => [
                                'prefix' => '$ ',
                                'suffix' => ' ¢',
                                'allowMinus' => false
                            ],
//                                'options' => $saveOptions,
                            'displayOptions' => $disOptions,
                            'saveInputContainer' => $saveCont,
                        ]) ?>
                    </div>
                    <div class="col-md-4">

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
                        echo DateRangePicker::widget([
//                                'model'=>$model,
//                                'attribute' => 'dateRange',
                                'name' => 'kvdate2',
                                'useWithAddon' => true,
                                'convertFormat' => true,
//                               'value'=> '14-11-2018 - 14-01-2019',
                               'value'=> $defaultValueDateRange,
                                'language' => Yii::$app->language,
                                'hideInput' => true,
                                'startAttribute' => 'Loan[start_date]',
                                'endAttribute' => 'Loan[end_date]',
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

                        <?= $form->field($model, 'frequency_payment')
                                 ->textInput(['data-parsley-required'=>"true",
                                     'data-parsley-type'=>"number",
                                     'data-parsley-min'=>"0",
                                     'placeholder'=>'Frecuencia de Pagos en días']) ?>
                    </div>
                </div>

                <div class="form-group">

                    <?= Html::button('Generar', ['class' => 'btn btn-primary','id'=>'generate']) ?>
                    <?php /*Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) */?>
                </div>

                <div id="feeBox" class="box collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coutas a pagar</h3>

                        <div class="box-tools pull-right">
                            <button id="collapsedBtn" type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                                <i class="fa fa-plus"></i></button>
<!--                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">-->
<!--                                <i class="fa fa-times"></i></button>-->
                        </div>
                    </div>
                    <div class="box-body" style="">
                        <div class="row">
                            <div class="col-md-6">
                                <?php /* $form->field($model, 'fee_payment')->widget(NumberControl::className(), [
                                    'maskedInputOptions' => [
                                        'prefix' => '$ ',
                                        'suffix' => ' ¢',
                                        'allowMinus' => false
                                    ],
//                                'options' => $saveOptions,
                                    'displayOptions' => $disOptions,
                                    'saveInputContainer' => $saveCont,
                                ])->label('Valor de las Cuotas') */
                                ?>

                                <?= $form->field($model, 'fee_payment')
                                    ->input('number', ['data-parsley-pattern'=>"^[0-9]*\,[0-9]{2}$"])
                                    ->label('Valor de las Cuotas') ?>

<!--                                <div class="form-group">-->
<!--                                    <label>Valor de las Cuotas</label>-->
<!--                                    <span class="input-group-addon">$</span>-->
<!--                                    <input type="text" class="form-control">-->
<!--                                    <span class="input-group-addon">.00</span>-->
<!--                                </div>-->

                                <div class="form-group">
                                    <?= Html::label('Cantidad de Coutas: ', null, ['id'=>'countFee'])?>
                                </div>

                                <div class="form-group">
                                    <?= Html::label('Total a cancelar: ', null, ['id'=>'total'])?>

                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <?= Html::label('Fechas de pago:')?>
                                <div class="table-responsive">
                                    <table id="data-table" class="table table-bordered nowrap table-condensed" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Fecha</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <?= Html::hiddenInput(['name'=>'payments'])?>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer" style="">
                        <div class="form-group">

                            <?= Html::button('Confirmar', [
                                'class' => 'btn btn-success',
                                'data-toggle'=>"modal",
                                'data-target'=>"#modal-default"
                            ])?>
                            <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                        </div>
                    </div>
                    <!-- /.box-footer-->

                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div> <!-- end box -->
    </div> <!-- end col -->
</div> <!-- end row -->

<!-- #modal -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Advertencia</h4>
            </div>
            <div class="modal-body">
                <p>¿Esta seguro que desea generar el crédito?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                <button id="aceptBtn" type="button" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    var loanId = '<?php echo $model->id; ?>';
</script>

<?php $this->registerJsFile('@web/js/loan/form.js', ['depends' => ['app\assets\DataTableAsset']]) ?>
