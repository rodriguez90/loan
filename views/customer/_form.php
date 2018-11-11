<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */

use app\assets\FormWizardAsset;

FormWizardAsset::register($this);  // $this represents the view object

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
                    <div id="wizard">
                        <ol>
                            <li>
                                Identificación
                            </li>
                            <li>
                                Contactos
                            </li>
                            <li>
                                Direción y Ubicación
                            </li>
                        </ol>
                        <!-- begin wizard step-1 -->
                        <div class="wizard-step-1">
                            <fieldset>
                                <legend class="pull-left width-full">Identificación</legend>
                                <!-- begin row -->
                                <div class="row">
                                    <!-- begin col-4 -->
                                    <div class="col-md-4">
                                        <div class="form-group block1">
                                            <label>Nombre</label>
                                            <input type="text"
                                                   name="Customer[first_name]"
                                                   placeholder="Juan"
                                                   class="form-control"
                                                   data-parsley-group="wizard-step-1"
                                                   value="<?= $model['first_name'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <!-- end col-4 -->
                                    <!-- begin col-4 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Apellidos</label>
                                            <input type="text" name="Customer[last_name]"
                                                   placeholder="Pérez"
                                                   class="form-control"
                                                   data-parsley-group="wizard-step-1"
                                                   value="<?= $model['last_name'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <!-- end col-4 -->
                                    <!-- begin col-4 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cédula</label>
                                            <input type="text" name="Customer[dni]"
                                                   placeholder="01234567890"
                                                   class="form-control"
                                                   data-parsley-group="wizard-step-1"
                                                   data-parsley-type="number"
                                                   data-parsley-maxlength="10"
                                                   data-parsley-minlength="10"
                                                   value="<?= $model['dni'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <!-- end col-4 -->
                                </div>
                                <!-- end row -->
                            </fieldset>
                        </div>
                        <!-- end wizard step-1 -->
                        <!-- begin wizard step-2 -->
                        <div class="wizard-step-2">
                            <fieldset>
                                <legend class="pull-left width-full">Información de Contacto</legend>
                                <!-- begin row -->
                                <div class="row">
                                    <!-- begin col-6 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Número de teléfono</label>
                                            <input type="text"
                                                   name="Customer[phone_number]"
                                                   placeholder="1234567890"
                                                   class="form-control"
                                                   data-parsley-group="wizard-step-2"
                                                   data-parsley-type="number"
                                                   value="<?= $model['phone_number'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <!-- end col-6 -->
                                    <!-- begin col-6 -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"
                                                   name="Customer[email]"
                                                   placeholder="algun@ejemplo.com"
                                                   class="form-control"
                                                   data-parsley-group="wizard-step-2"
                                                   data-parsley-type="email"
                                                   value="<?= $model['email'] ?>"
                                                   required />
                                        </div>
                                    </div>
                                    <!-- end col-6 -->
                                </div>
                                <!-- end row -->
                            </fieldset>
                        </div>
                        <!-- end wizard step-2 -->
                        <!-- begin wizard step-3 -->
                        <div class="wizard-step-3">
                            <fieldset>
                                <legend class="pull-left width-full">Direción y Ubicación</legend>
                                <!-- begin row -->
                                <div class="row">
                                    <!-- begin col-4 -->
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'address')->textarea(['data-parsley-group'=>"wizard-step-3",'data-parsley-required'=>"true"])?>
                                    </div>
                                    <!-- end col-4 -->
                                    <!-- begin col-4 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ubicación</label>
                                            <div class="controls">
                                                <input type="text"
                                                       name="Customer[location]"
                                                       placeholder=""
                                                       class="form-control"
                                                       data-parsley-group="wizard-step-3"
                                                       value="<?= $model['location'] ?>"
                                                       required />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col-4 -->
                                </div>
                                <!-- end row -->
                            </fieldset>
                        </div>
                        <!-- end wizard step-3 -->
                        <!-- begin wizard step-4 -->
                        <div>
                            <div class="jumbotron m-b-0 text-center">
                                <h1>Registro Exitoso</h1>
                            </div>
                        </div>
                        <!-- end wizard step-4 -->
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-12 -->
</div>
<!-- end row -->