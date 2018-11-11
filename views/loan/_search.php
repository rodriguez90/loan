<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="loan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'banker_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'porcent_interest') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'refinancing_id') ?>

    <?php // echo $form->field($model, 'frequency_payment') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'fee_payment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
