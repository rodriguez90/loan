<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$this->title = "Cliente: " . $model->first_name . " " . $model->last_name ;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-6 col-xs-6 col-md-offset-3">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>

                <div class="box-tools pull-right">
                    <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Está seguro que desea eliminar este cliente?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'first_name',
                        'last_name',
                        'dni',
                        'email:email',
                        'phone_number',
                        'location',
                        'address',
                        'created_at:datetime',
                        'updated_at:datetime',
                        'created_by.username',
                    ],
                    'options'=>['class' => 'table table-striped table-bordered table-condensed detail-view']
                ]) ?>
            </div>
        </div>
    </div>
</div>
