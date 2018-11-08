<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
        <!-- begin panel -->
        <div class="box box-success">
            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'customer_id')->textInput() ?>

                        <?= $form->field($model, 'amount')->textInput() ?>

                        <?= $form->field($model, 'porcent_interest')->textInput() ?>
                    </div>
                    <div class="col-md-6">

                        <?= $form->field($model, 'frequency_payment')->textInput() ?>

                        <?= $form->field($model, 'start_date')->textInput() ?>

                        <?= $form->field($model, 'end_date')->textInput() ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
