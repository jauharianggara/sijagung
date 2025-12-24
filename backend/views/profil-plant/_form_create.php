<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use kartik\widgets\SwitchInput;
use backend\models\ProfilePlatJenisProduksi;
use backend\models\JenisProduksi;
/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */
/* @var $form yii\widgets\ActiveForm */
?>
  <?= $form->field($model, 'nama_plant')->textInput(['maxlength' => true])->label('Nama Perusahaan'); ?>
  <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('User Name'); ?>
  <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email'); ?> 
