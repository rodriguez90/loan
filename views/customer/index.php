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
    <div class="col-lg-12 col-xs-6">
        <div class="box box-solid">
                <div class="box-header with-border">
                    <?= Html::a('Nuevo Cliente', ['create'], ['class' => 'btn btn-success btn']) ?>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

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
                            //'phone_number',
                            //'location',
                            //'address',
                            //'created_at',
                            //'updated_at',
                            //'created_by',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
    </div>
</div>
