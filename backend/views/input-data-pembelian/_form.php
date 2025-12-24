<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
use backend\models\User;
use backend\models\Provinsi;

/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */
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

        <!--
		<div class="col-md-6">
			<?= Html::label('Kadar Air Purchasing Order','inputdatapembelian-kapo', ['class' => 'form-label'])?>
			<?= $form->field($model, 'kapo', [
							'template' => '
							{input}
							<div class="input-group-append">
								<span class="input-group-text" id="addon-kapo">%</span>
							</div>
							{error}{hint}',
							'options'=>[
								'class'=>'input-group',
							],
							'inputOptions'=>[
								'class'=>'form-control',
								'aria-describedby'=>'addon-kapo',
							],
			]); ?>
		</div>
        -->
</div>

<div class="row">
            <!--
		<div class="col-md-6">
			<?= Html::label('Harga Purchasing Order','inputdatapembelian-harga_po', ['class' => 'form-label'])?>
			<?= $form->field($model, 'harga_po', [
							'template' => '
							<div class="input-group-prepend">
								<span class="input-group-text" id="addon-harga_po">Rp/Kg</span>
							</div>
							{input}
							{error}{hint}',
							'options'=>[
								'class'=>'input-group',
							],
							'inputOptions'=>[
								'class'=>'form-control',
								'aria-describedby'=>'addon-harga_po',
							],
			]); ?>
		</div>
        -->

		<div class="col-md-6">
			<?= Html::label('Volume yang diterima','inputdatapembelian-quantity_terima', ['class' => 'form-label'])?>
			<?= $form->field($model, 'quantity_terima', [
							'template' => '{input}
							<div class="input-group-append">
								<span class="input-group-text" id="addon-quantity_terima">Kg</span>
							</div>
							{error}{hint}',
							'options'=>[
								'class'=>'input-group',
							],
							'inputOptions'=>[
								'class'=>'form-control',
								'aria-describedby'=>'addon-quantity_terima',
							],
			]); ?>
		</div>
</div>
</br>
<div class="row">
		<div class="col-md-6">
			<?= Html::label('Harga Terima','inputdatapembelian-harga_terima', ['class' => 'form-label'])?>
			<?= $form->field($model, 'harga_terima', [
							'template' => '
							<div class="input-group-prepend">
								<span class="input-group-text" id="addon-quantity_terima">Rp/Kg</span>
							</div>
							{input}
							{error}{hint}',
							'options'=>[
								'class'=>'input-group',
							],
							'inputOptions'=>[
								'class'=>'form-control',
								'aria-describedby'=>'addon-harga_terima',
							],
			]); ?>
		</div>

		<div class="col-md-6">
			<?= Html::label('Kadar Air Terima','inputdatapembelian-ka_terima', ['class' => 'form-label'])?>
			<?= $form->field($model, 'ka_terima', [
							'template' => '{input}
							<div class="input-group-append">
								<span class="input-group-text" id="addon-ka_terima">%</span>
							</div>
							{error}{hint}',
							'options'=>[
								'class'=>'input-group',
							],
							'inputOptions'=>[
								'class'=>'form-control',
								'aria-describedby'=>'addon-ka_terima',
							],
			]); ?>
		</div>
</div>
</br>
<div class="row">
	<div class="col-md-12">
				<?php
			$dataProvinsi = ArrayHelper::map(
			Provinsi::find()->asArray()->all(),
			'id', 'nama');

			echo $form->field($model, 'sumber')->widget(Select2::classname(), [
				'data' => $dataProvinsi,
				'options' => [
				'placeholder' => 'Select Provinsi ...',
				],
				'pluginOptions' => [
				'allowClear' => true,
				'multiple' => true,
				],
			])->label('Asal Pembelian'); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		 <?php
		 if(\Yii::$app->authManager-> getAssignment('plant',\Yii::$app->user->id)){
			 $dataSupplier = ArrayHelper::map(\backend\models\ProfilSupplier::find()->where(['id_plant'=>\Yii::$app->user->id])->asArray()->all(),'id', 'nama');
		 }else{
			 $dataSupplier = ArrayHelper::map(\backend\models\ProfilSupplier::find()->where(['id_plant'=>$model->id_plant])->asArray()->all(),'id', 'nama');
		 }
			echo $form->field($model, 'supplier')->widget(Select2::classname(), [
				'data' => $dataSupplier,
				'options' => [
				'placeholder' => 'Select Pemasok ...',
				],
				'pluginOptions' => [
				'allowClear' => true,
				'multiple' => true,
				],
			])->label('Pemasok --- [Isi dulu Master Pemasok jika masih kosong]'); ?>
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
