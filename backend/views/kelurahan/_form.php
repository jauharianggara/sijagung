<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\Kecamatan;

/* @var $this yii\web\View */
/* @var $model backend\models\Kelurahan */
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

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>


   <?php 
  $dataKecamatan = ArrayHelper::map(Kecamatan::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, 'id_kecamatan')->dropDownList(
    $dataKecamatan ,
    [
      'id'=>'id',
      'prompt'=>'Pilih Kecamatan',
    ])->label('Kecamatan');
  ?>
