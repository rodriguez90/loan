<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Customer;
use kartik\number\NumberControl;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */
/* @var $form yii\widgets\ActiveForm */

$url = \yii\helpers\Url::to(['/customer/customer-list']);

$customerName = "";

if($model->customer_id)
{
    $customer = Customer::findOne($model->customer_id);
    $customerName = $customer->first_name . ' ' . $customer->last_name;
}

//var_dump(!empty($model->start_date) and !empty($model->end_date ) );
//var_dump(date('d-m-Y', strtotime($model->start_date)). ' - ' . date('d-m-Y', strtotime($model->end_date)));
//var_dump(date('d-m-Y', strtotime($model->start_date)));
//var_dump(date('d-m-Y', strtotime($model->end_date)));die;

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
                    <div class="col-md-6">
                        <div class="form-group">
<!--                            <label>Cliente</label>-->
                            <?php /* \kartik\select2\Select2::widget([
                                'bsVersion' => '3.x',
                                'name' => 'Loan[customer_id]',
                                'initValueText' => $customerName,
                                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                                'options' => ['placeholder' => 'Seleccione el cliente'],
                                'pluginOptions' => [
                                    'allowClear' => false,
                                    'minimumInputLength' => 3,
                                    'ajax' => [
                                        'url' => $url,
                                        'dateType' => 'json',
                                        'data' => new \yii\web\JsExpression("function(params) {return {q:params.term};}")
                                    ],
                                    'escapeMarkup' => new \yii\web\JsExpression("function(markup) {return markup;}"),
                                    'templateResult' => new \yii\web\JsExpression("function(customer) {return customer.text;}"),
                                    'templateSelection' => new \yii\web\JsExpression("function(customer) {return customer.text;}"),
                                ],
                            ]);*/ ?>

                            <?= $form->field($model, 'customer_id')->widget(\kartik\select2\Select2::classname(), [
                                'bsVersion' => '3.x',
//                                'name' => 'Loan[customer_id]',
                                'initValueText' => $customerName,
                                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                                'options' => ['placeholder' => 'Seleccione el cliente'],
                                'pluginOptions' => [
                                    'allowClear' => false,
                                    'minimumInputLength' => 3,
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
                        </div>

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

<!--                        <div class="input-group">-->
<!--                            <span class="input-group-addon">$</span>-->
<!--                            <input type="text" class="form-control"/>-->
<!--                            <span class="input-group-addon">.00</span>-->
<!--                        </div>-->

                        <?= $form->field($model, 'porcent_interest')
                                 ->dropDownList([0 => '', 10 => '10%', 15 => '15%', 20 => '20%', 25 => '25%'], [
                                    'data-parsley-required' => "true",
                                    'data-parsley-type' => "integer",
                                    'data-parsley-type' => "number",
                                    'data-parsley-min'=>"10"]
                                 )?>
                    </div>
                    <div class="col-md-6">

                        <?= $form->field($model, 'fee_payment')->widget(NumberControl::className(), [
                            'maskedInputOptions' => [
                                'prefix' => '$ ',
                                'suffix' => ' ¢',
                                'allowMinus' => false
                            ],
//                                'options' => $saveOptions,
                            'displayOptions' => $disOptions,
                            'saveInputContainer' => $saveCont,
                        ]) ?>

                        <?= $form->field($model, 'frequency_payment')
                                 ->textInput(['data-parsley-required'=>"true",
                                     'data-parsley-type'=>"number",
                                     'placeholder'=>'Frecuencia de Pagos en días']) ?>
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
                                /*'value'=> !empty($model->start_date) and !empty($model->end_date ) ?
                                        date('d-m-Y', strtotime($model->start_date)). ' - ' . date('d-m-Y', strtotime($model->end_date)): '',*/
                                'language' => Yii::$app->language,
                                'hideInput' => true,
                                'startAttribute' => 'Loan[start_date]',
                                'endAttribute' => 'Loan[end_date]',
                                'pluginOptions'=>[
                                    'locale'=>['format' => 'd-m-Y'],
                                    'separator' => '-',
                                    'opens' => 'left',
                                    'showDropdowns'=>true
                                ]
                            ]) . $addon;
//                        echo '</div>';
                        echo '</div>';
                        ?>

                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div> <!-- end box -->
    </div> <!-- end col -->
</div> <!-- end row -->
