<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\KotaKabupaten;
use backend\models\Provinsi;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminKotaKabupaten */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile("@web/plugins/datepicker/bootstrap-datepicker.js", [
	'depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerCssFile("@web/plugins/datepicker/datepicker3.css",[
    'depends' => [BootstrapAsset::className()],
]);

$this->registerJs('

	(function($){
	$.fn.datepicker.dates["id"] = {
			days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
			daysShort: ["Mgu", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Mgu"],
			daysMin: ["Mg", "Sn", "Sl", "Ra", "Ka", "Ju", "Sa", "Mg"],
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
			today: "Hari Ini",
			clear: "Kosongkan"
		};
	}(jQuery));

	$(".tanggal").datepicker({
		language : \'id\',
		format: \'yyyy-mm-dd\',
		autoclose: true,
		clearBtn:true,
		todayHighlight:true,
		toggleActive:true,
  });

');
?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_pic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hp_pic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


   <?php 
  $dataKotaKabupaten = ArrayHelper::map(KotaKabupaten::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, 'id_kota_kabupaten')->dropDownList(
    $dataKotaKabupaten ,
    [
      'id'=>'id',
      'prompt'=>'Pilih Kota Kabupaten',
    ])->label('Kota Kabupaten');
  ?>
   <?php 
  $dataUser = ArrayHelper::map(User::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, 'id_user')->dropDownList(
    $dataUser ,
    [
      'id'=>'id',
      'prompt'=>'Pilih User',
    ])->label('User');
  ?>
   <?php 
  $dataProvinsi = ArrayHelper::map(Provinsi::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, 'id_provinsi')->dropDownList(
    $dataProvinsi ,
    [
      'id'=>'id',
      'prompt'=>'Pilih Provinsi',
    ])->label('Provinsi');
  ?>
