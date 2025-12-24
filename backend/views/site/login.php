<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'SIJAGUNG 2.0';

$fieldOptions1 = [
    'options' => ['class' => 'form-group m-t-40'],
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group'],
    'inputTemplate' => "{input}"
];

?>

<div class="login-box card">
    <div class="card-body">
          <?php $form = ActiveForm::begin(['id' => 'loginform', 'enableClientValidation' => false,'options'=>['class'=>'form-horizontal form-material']]); ?>

            <a href="javascript:void(0)" class="text-center db"><img src="../logo/logo.png" alt="Home" /><br/>SIJAGUNG 2.0</a>

            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <?= Html::submitButton('<i class="glyphicon glyphicon-log-in"></i> Masuk Aplikasi', ['class' => 'btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light', 'name' => 'login-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
