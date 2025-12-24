<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\DataDistribusiPakan */

$this->title = 'Laporan Pemasok';
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pemasok', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Laporan Pemasok';

?>
<?php $form = ActiveForm::begin(['method'=>'get']); ?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?= Html::encode($this->title) ?></h3>
      </div>

			<div class="card-body">
	   <?= $form->field($model, 'tahun')->textInput() ?>

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

    <?php
		if(\Yii::$app->authManager-> getAssignment('admin',\Yii::$app->user->id)){
			$dataPlant=ArrayHelper::map(\backend\models\ProfilPlant::find(['id_user','nama_plant'])->orderBy('nama_plant')->asArray()->all(), 'id_user', 'nama_plant');

      echo $form->field($model, 'id_plant')->dropDownList(
              $dataPlant,
              [
                'id'=>'plant',
                'prompt'=>'-- Pilih Plant --',
              ])->label('Plant');
		}

    ?>
			</div>


			<div class="card-footer">
				<div class="pull-right">
					<?= Html::submitButton('<i class="fa fa-view"></i> Tampil', ['class' =>'btn btn-primary']) ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
