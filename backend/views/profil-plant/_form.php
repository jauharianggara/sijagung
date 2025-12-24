<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;
use kartik\widgets\SwitchInput;
use backend\models\ProfilePlatJenisProduksi;
use backend\models\JenisProduksi;
/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */
/* @var $form yii\widgets\ActiveForm */
?>

        <?= $form->field($model, 'kapasitas_terpasang', [
                'template' => '{label}<div class="input-group">{input} 
                <span class="input-group-addon">Ton/Tahun</span></div>{error}{hint}'
        ])->label('Kapasitas Produksi Terpasang'); ?>


		<?php
			$dataJenisProduksi=ArrayHelper::map(\backend\models\JenisProduksi::find()->orderBy('id')->asArray()->all(), 'id', 'nama');
			echo $form->field(
                $model, 
                'jenisPorduksi'
                )
                ->checkboxList(
                    $dataJenisProduksi,
                    [
                        'separator'=>'<br/>',
                        'onclick' => "$(this).val( $('input:checkbox:checked').val()); ",//if you use required as a validation rule you will need this for the time being until a fix is in place by yii2
                        'item' => function($index, $label, $name, $checked, $value) {
                            $checkedLabel = '';
                            if($checked ==  1){
                                $checkedLabel = 'checked';
                            }
                            $checked.' ';
                            return "
                                    <input id='ProfilPlant_jenisPorduksi_{$value}' type='checkbox' {$checkedLabel} name='{$name}' value='{$value}'>
                                    <label for='ProfilPlant_jenisPorduksi_{$value}'>{$label}</label>
                                    ";
                        }
                    ]
                )->label('Jenis Pakan yang Diproduksi');
		?>

		<?php
					echo $form->field($model, 'silo')->dropDownList(
									[
										'Ada'=>'Ada',
										'Tidak Ada'=>'Tidak Ada',
									],
									[
										'id'=>'silo',
										'prompt'=>'-- Pilih Silo --',
									])->label('Ketersediaan Silo');
				?>

			<?= $form->field($model, 'kapasitas_silo', [
					'template' => '{label}<div class="input-group">{input}
					<span class="input-group-addon">Ton</span></div>{error}{hint}'
			])->label('Kapasitas Silo'); ?>
			
		<?php
					echo $form->field($model, 'dryer')->dropDownList(
									[
										'Ada'=>'Ada',
										'Tidak Ada'=>'Tidak Ada',
									],
									[
										'id'=>'dryer',
										'prompt'=>'-- Pilih Dryer --',
									])->label('Ketersediaan Dryer');
				?>
			<?= $form->field($model, 'kapasitas', [
					'template' => '{label}<div class="input-group">{input}
					<span class="input-group-addon">Ton/Hari</span></div>{error}{hint}'
			])->label('Kapasitas Dryer'); ?>
