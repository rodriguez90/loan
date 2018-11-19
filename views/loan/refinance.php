<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'Refinanciar Préstamo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Préstamos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>

    <?= $this->render('_form', [
        'model' => $model,
        'scenario'=>\app\models\Loan::SCENARIO_REFINANCE
    ]) ?>


<?php $this->registerJsFile('@web/js/loan/refinance.js', ['depends' => ['app\assets\FormPluginsAsset','app\assets\DataTableAsset'] ]) ?>
