<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use app\models\Customer;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */

use app\assets\FormPluginsAsset;


FormPluginsAsset::register($this);  // $this represents the view object

?>

<?php if ($model->hasErrors()) {
    \Yii::$app->getSession()->setFlash('error', $model->getErrorSummary(true));
}
?>

<!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
        <!-- begin panel -->
        <div class="box box-success">
<!--            <div class="box-header with-border">-->
<!--                <h4 class="box-title">Nuevo Cliente</h4>-->
<!--            </div>-->
            <div class="box-body">
                <?php $form = ActiveForm::begin(['options'=>
                    [
                        'id'=>'user-form',
                        'enableClientScript' => false,
                        'action'=>"/",
                        'method'=>"POST",
                        'data-parsley-validate'=>'true',
                        'name'=>"form-wizard"]]); ?>
                            <!-- begin row -->
                            <div class="row">
                                <!-- begin col-4 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text"
                                               name="Customer[first_name]"
                                               placeholder="Juan"
                                               class="form-control"
                                               data-parsley-required="true"
                                               value="<?= $model['first_name'] ?>"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label>Apellidos</label>
                                        <input type="text" name="Customer[last_name]"
                                               placeholder="Pérez"
                                               class="form-control"
                                               data-parsley-required="true"
                                               value="<?= $model['last_name'] ?>"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label>Cédula</label>
                                        <input type="text" name="Customer[dni]"
                                               placeholder="01234567890"
                                               class="form-control"
                                               data-parsley-required="true"
                                               data-parsley-type="number"
                                               data-parsley-maxlength="10"
                                               data-parsley-minlength="10"
                                               value="<?= $model['dni'] ?>"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label>Número de teléfono</label>
                                        <input type="text"
                                               name="Customer[phone_number]"
                                               placeholder="1234567890"
                                               class="form-control"
                                               data-parsley-type="number"
                                               data-parsley-required="true"
                                               value="<?= $model['phone_number'] ?>"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email"
                                               name="Customer[email]"
                                               placeholder="algun@ejemplo.com"
                                               class="form-control"
                                               data-parsley-required="true"
                                               data-parsley-type="email"
                                               value="<?= $model['email'] ?>"
                                               required />
                                    </div>
                                </div>
                                <!-- end col-4 -->
                                <!-- begin col-4 -->
                                <div class="col-md-6">
                                    <?= $form->field($model, 'address')->textarea(['data-parsley-required'=>"true"])?>

                                    <div class="form-group">
                                        <label>Ubicación</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-map-marker"></i>
                                            </div>
                                            <input type="text"
                                                   name="Customer[location]"
                                                   placeholder=""
                                                   class="form-control"
                                                   data-parsley-required="true"
                                                   data-parsley-type="url"
                                                   value="<?= $model['location'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <?= $form->field($model, 'active')->widget(SwitchInput::className(),[
                                            'pluginOptions'=>[
                                                    'onText'=>Customer::ACTIVE_LABEL[Customer::ACTIVE],
                                                    'offText'=>Customer::ACTIVE_LABEL[Customer::INACTIVE]
                                            ]
                                    ]) ?>
                                </div>
                                <!-- end col-4 -->
                            </div>
                            <!-- end row -->
                <div class="form-group pull-right">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Cancelar', ['class' => 'btn btn-default', 'onclick'=>'window.history.go(-1)']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-12 -->
</div>
<!-- end row -->