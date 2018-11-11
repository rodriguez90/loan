<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Modificar Cobro: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cobros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
?>
<div class="payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
