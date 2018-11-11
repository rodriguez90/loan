<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Nuevo Cobro';
$this->params['breadcrumbs'][] = ['label' => 'Cobros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
