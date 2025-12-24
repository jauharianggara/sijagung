<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\ProfilPlant;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlantHistory */
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

    <?= $form->field($model, 'tanggal')->textInput() ?>

    <?= $form->field($model, 'kapasitas_silo_sebelumnya')->textInput() ?>

    <?= $form->field($model, 'kapasitas_silo_sekarang')->textInput() ?>

    <?= $form->field($model, 'kapasitas_dryer_sebelumnya')->textInput() ?>

    <?= $form->field($model, 'kapasitas_dryer_sekarang')->textInput() ?>

    <?= $form->field($model, 'kapasitas_produksi_sebelumnya')->textInput() ?>

    <?= $form->field($model, 'kapasitas_produksi_sekarang')->textInput() ?>


   <?php 
  $dataProfilPlant = ArrayHelper::map(ProfilPlant::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, 'id_plant')->dropDownList(
    $dataProfilPlant ,
    [
      'id'=>'id',
      'prompt'=>'Pilih Profil Plant',
    ])->label('Profil Plant');
  ?>
