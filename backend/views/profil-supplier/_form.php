<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\Provinsi;
use backend\models\KotaKabupaten;
use backend\models\Kecamatan;
use backend\models\Kelurahan;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilSupplier */
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
    <?= $form->field($model, 'telepon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>


    <?php
		$dataProvinsi=ArrayHelper::map(\backend\models\Provinsi::find()->select(['id','nama'])->orderBy('nama')->asArray()->all(), 'id', 'nama');
		echo $form->field($model, 'id_provinsi')->dropDownList(
							$dataProvinsi,
							[
								'id'=>'id_provinsi',
								'prompt'=>'-- Pilih Provinsi --',
                'onchange'=>'
          				$.post( "'.Yii::$app->urlManager->createUrl('kota-kabupaten/list?id=').'"+$(this).val(), function( data ) {
          				  $( "select#id_kota_kabupaten" ).html( data );
          				});
          			'
							])->label('Provinsi');
	?>
	<?php
    if(!$model->isNewRecord){
      $dataKotaKabupaten=ArrayHelper::map(\backend\models\KotaKabupaten::find()->select(['id','nama'])->where(['id_provinsi'=>$model->ip_provinsi])->orderBy('nama')->asArray()->all(), 'id', 'nama');
    }
		echo $form->field($model, 'id_kota_kabupaten')->dropDownList(
							$dataKotaKabupaten,
							[
								'id'=>'id_kota_kabupaten',
								'prompt'=>'-- Pilih Kota / Kabupaten --',
                'onchange'=>'
          				$.post( "'.Yii::$app->urlManager->createUrl('kecamatan/list?id=').'"+$(this).val(), function( data ) {
          				  $( "select#id_kecamatan" ).html( data );
          				});
          			'
							])->label('Kota / Kabupaten');
	?>
	<?php
    if(!$model->isNewRecord){
  		$dataKecamatan=ArrayHelper::map(\backend\models\Kecamatan::find()->select(['id','nama'])->where(['id_kota_kabupaten'=>$model->ip_kota])->orderBy('nama')->asArray()->all(), 'id', 'nama');
    }
    echo $form->field($model, 'id_kecamatan')->dropDownList(
							$dataKecamatan,
							[
								'id'=>'id_kecamatan',
								'prompt'=>'-- Pilih Kecamatan --',
                                'onchange'=>'
                                    $.post( "'.Yii::$app->urlManager->createUrl('kelurahan/list?id=').'"+$(this).val(), function( data ) {
                                    $( "select#id_kelurahan" ).html( data );
                                    });
                                '
							])->label('Kecamatan');
	?>
	<?php
    if(!$model->isNewRecord){
  		$dataKelurahan=ArrayHelper::map(\backend\models\Kelurahan::find()->select(['id','nama'])->where(['id_kecamatan'=>$model->ip_kecamatan])->orderBy('nama')->asArray()->all(), 'id', 'nama');
    }
    echo $form->field($model, 'id_kelurahan')->dropDownList(
							$dataKelurahan,
							[
								'id'=>'id_kelurahan',
								'prompt'=>'-- Pilih Kelurahan --',
							])->label('Kelurahan');
	?>
