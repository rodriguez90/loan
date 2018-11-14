<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="box box-solid">
                <div class="box-header with-border">
                    <?= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success btn']) ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">

                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'label'=>'Nombre',
                                'attribute'=>'first_name'
                            ],
                            [
                                'label'=>'Apellidos',
                                'attribute'=>'last_name'
                            ],
                            [
                                'label'=>'Cedúla',
                                'attribute'=>'dni'
                            ],
                            'email:email',
                            [
                                'label'=>'Teléfono',
                                'attribute'=>'phone_number'
                            ],
                            [
                                'label'=>'Estado',
                                'attribute'=>'active',
                                'content' => function ($data) {
                                    return $data['active'] ? '<span class="label label-success  pull-left">Activo</span>' : '<span class="label label-danger">Inactivo</span>';
                                },
                                'filter' => ['0' =>'Inactivo', '1' =>'Activo',],
                            ],
                            //'phone_number',
                            [
                                'attribute'=>'location',
                                'format'=>'raw',
//                                'contentOptions'=>['class'=>'text-truncate'],
                                'value' => function ($data) {
//                                    return Html::a($data->location,$data->location, ['style'=>'text-overflow:ellipsis;white-space:nowrap;overflow:hidden;width:70px'] );
//                                    return Html::a($data->location,$data->location, ['class'=>'text-ellipsis'] );
                                    return Html::a('Ir',$data->location);
                                },
                            ],
                            //'address',
                            //'created_at',
                            //'updated_at',
                            //'created_by',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                        'tableOptions'=>['class'=>'table table-striped table-bordered table-condensed']
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
    </div>
</div>
