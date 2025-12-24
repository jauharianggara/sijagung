<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\InputDataStok */
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

<div class="row">
 <div class="col-md-3">
	 <?php


		for($i=1; $i<13; $i++){
			$bulan[$i]=$i;
		}
			echo $form->field($model, 'bulan')->dropDownList(
						[
									"1"=>"Januari",
									"2"=>"Februari",
									"3"=>"Maret",
									"4"=>"April",
									"5"=>"Mei",
									"6"=>"Juni",
									"7"=>"Juli",
									"8"=>"Agustus",
									"9"=>"September",
									"10"=>"Oktober",
									"11"=>"November",
									"12"=>"Desember",
									],
						[
							'id'=>'bulan',
							'prompt'=>'-- Pilih Bulan --',
						])->label('Bulan');
		?>
		</div>

	<div class="col-md-3">
		<?php
		$tahunAwal  = date("Y")-1;
		$tahunAkhir  = date("Y")+5;
		for($i = $tahunAwal ;$i <= $tahunAkhir;$i++){
				$tahun[$i]=$i;
			}
					echo $form->field($model, 'tahun')->dropDownList(
									$tahun,
									[
										'id'=>'tahun',
										'prompt'=>'-- Pilih Tahun --',
									])->label('Tahun');
				?>
		</div>
</div>
<div class="row">
<div class="col-md-6">
		<?= Html::label('Jumlah Stok','inputdatastok-jumlah_stok', ['class' => 'form-label'])?>
		<?= $form->field($model, 'jumlah_stok', [
			'template' => '
			{input}
			<div class="input-group-append">
				<span class="input-group-text" id="addon-jumlah_stok">KG</span>
			</div>
			{error}{hint}',
			'options'=>[
				'class'=>'input-group',
			],
			'inputOptions'=>[
				'class'=>'form-control',
				'aria-describedby'=>'addon-jumlah_stok',
			],
		]); ?>
</div>

	<div class="col-md-6">
		<?= Html::label('Kecukupan Produksi','inputdatastok-kecukupan_produksi', ['class' => 'form-label'])?>
		<?= $form->field($model, 'kecukupan_produksi', [
				'template' => '
				{input}
				<div class="input-group-append">
					<span class="input-group-text" id="addon-jumlah_stok">Hari</span>
				</div>
				{error}{hint}',
				'options'=>[
					'class'=>'input-group',
				],
				'inputOptions'=>[
					'class'=>'form-control',
					'aria-describedby'=>'addon-kecukupan_produksi',
				],
		]); ?>
	</div>
</div>

<?php
	if($model->isNewRecord){
		echo "<div class='text-center'>";
		echo "Dengan ini kami menyatakan bahwa seluruh data yg kami masukkan ke dalam sistem adalah benar dan dapat dipertanggungjawabkan";
		echo $form->field($model, 'agreement',
			[
				'template' => '{input}{label}{error}{hint}',
			])->checkBox([],false);
		echo "</div>";
	}
?>
