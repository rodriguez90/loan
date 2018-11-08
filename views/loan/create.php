<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'Nuevo Préstamo';
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
