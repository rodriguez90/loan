<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'Nuevo Préstamo';
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
        'scenario'=>\app\models\Loan::SCENARIO_CREATE
    ]) ?>

<?php $this->registerJsFile('@web/js/loan/form.js', ['depends' => ['app\assets\DataTableAsset']]) ?>
