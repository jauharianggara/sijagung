<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */
/* @var $form yii\widgets\ActiveForm */
?>
    <?= $form->field($model, 'nama_lengkap')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telpon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

