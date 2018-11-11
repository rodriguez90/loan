<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LoanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Préstamos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12 col-xs-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <?= Html::a('Nuevo Préstamo', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
            <div class="box-body">
                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
//                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'customer.first_name',
//                        'banker',
                        'amount',
                        'porcent_interest',
                        [
                            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                            'attribute' => 'status',
                            'value' => function($data) {
                                return \app\models\Loan::STATUS_LABEL[$data['status']];
                            },
                            'filter' => ['0' =>'Inactivo', '1' =>'Activo' , '2'=>'Cerrado',],
                        ],
                        //'refinancing_id',
//                        'frequency_payment',
                        'start_date',
                        'end_date',
                        //'created_at',
                        //'updated_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
