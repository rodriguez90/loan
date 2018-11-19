<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'Modificar Préstamo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>

    <?= $this->render('_form', [
        'model' => $model,
        'scenario'=>\app\models\Loan::SCENARIO_UPDATE
    ]) ?>


<?php $this->registerJsFile('@web/js/loan/form.js', ['depends' => ['app\assets\DataTableAsset']]) ?>
