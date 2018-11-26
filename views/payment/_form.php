<?php

use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\number\NumberControl;
use app\models\Payment;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */

$url = \yii\helpers\Url::to(['/loan/loan-list']);

$loanFullName = "";

if($model->loan_id)
{
    $loanFullName = $model->loan->getFullName();
}

?>

<?php if ($model->hasErrors()) {
    \Yii::$app->getSession()->setFlash('error', $model->getErrorSummary(true));
}
?>

    <style>
        .bootstrap-switch .bootstrap-switch-handle-on,.bootstrap-switch .bootstrap-switch-handle-off, .bootstrap-switch .bootstrap-switch-label
        {
            height: auto !important;
        }
    </style>



    <!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
        <!-- begin box -->
        <div class="box box-success">
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'loan_id')->widget(\kartik\select2\Select2::classname(), [
                    'bsVersion' => '3.x',
                    'initValueText' => $loanFullName,
                    'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Seleccione el préstamo'],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'minimumInputLength' => 1,
                        'ajax' => [
                            'url' => $url,
                            'dateType' => 'json',
                            'data' => new \yii\web\JsExpression("function(params) {return {q:params.term};}")
                        ],
                        'escapeMarkup' => new \yii\web\JsExpression("function(markup) {return markup;}"),
                        'templateResult' => new \yii\web\JsExpression("function(loan) {return loan.text;}"),
                        'templateSelection' => new \yii\web\JsExpression("function(loan) {return loan.text;}"),
                    ],
                ]);?>

                <?php echo $form->field($model, 'payment_date', [
//                        'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
                        'options'=>['class'=>'drp-container form-group',  'readonly'=>$model->isNewRecord],

                    ])->widget(DateRangePicker::classname(), [

                        'value'=>date('d-m-Y'),
                        'useWithAddon'=>true,
                        'convertFormat'=>true,
                        'hideInput' => true,
                        'pluginOptions'=>[
                            'locale'=>['format' => 'd-m-Y'],
                            'singleDatePicker'=>true,
                            'showDropdowns'=>true
                    ]]);
                ?>

                <?= $form->field($model, 'amount')->widget(NumberControl::className(), [
                    'maskedInputOptions' => [
                        'prefix' => '$ ',
                        'suffix' => ' ¢',
                        'allowMinus' => false
                    ],
                    'displayOptions' => $disOptions,
                    'saveInputContainer' => $saveCont,
                ]) ?>

                <?= $form->field($model, 'status')->widget(SwitchInput::className(),[
                    'bsVersion' => '3.x',
                    'inlineLabel'=>false,
                    'pluginOptions'=>[
                        'size'=>'mini',
//                        'onstyle'=>'success',
//                        'offstyle'=>'danger',
                        'onText'=>Payment::STATUS_LABEL[Payment::COLLECTED],
                        'offText'=>Payment::STATUS_LABEL[Payment::PENDING],
                        'onColor'=>'success',
                        'offColor'=>'danger',
                    ],
                ]) ?>


                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    var scenario = '<?php echo !$model->isNewRecord? 'update':'create'; ?>';
    loan = <?php echo json_encode(['id'=>$model->loan->id,
        'startDate'=>$model->loan->start_date,
        'endDdate'=>$model->loan->end_date,
        'feePayment'=>$model->loan->fee_payment,
        'amountUnPaid'=>$model->loan->getAmountUnPaid(),
    ]); ?>;
</script>

<?php $this->registerJsFile('@web/js/payment/form.js', ['depends' => ['app\assets\FormPluginsAsset']]) ?>