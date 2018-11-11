<?php

use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */

$url = \yii\helpers\Url::to(['/loan/loan-list']);

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

                <?= $form->field($model, 'loan_id')->widget(\kartik\select2\Select2::classname(), [
                    'bsVersion' => '3.x',
                    'initValueText' => $model->loan_id,
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

                <?php

                    echo $form->field($model, 'payment_date', [
                        'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
                        'options'=>['class'=>'drp-container form-group']
                    ])->widget(DateRangePicker::classname(), [

                        'value'=>date('d-m-Y'),
                        'useWithAddon'=>true,
                        'convertFormat'=>true,
                        'hideInput' => true,
                        'pluginOptions'=>[
                            'locale'=>['format' => 'd-m-Y'],
                            'singleDatePicker'=>true,
                            'showDropdowns'=>true
                        ]
                    ]);

                ?>

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

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
