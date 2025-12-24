<?php
use yii\helpers\Html;
use yii\grid\GridView;
use hscstudio\mimin\components\Mimin;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DataHargaPakanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan Pemasok';
$this->params['breadcrumbs'][] = $this->title;

?>
	<div class="data-harga-pakan-index">

  <div class="row">
	<div class="col-xs-12">
		<div class="card">
			<div class="card-header with-border hidden-print">
        <?php $form = ActiveForm::begin([
          'method'=>'post',
        ]); ?>
		     <?= Html::a('<i class="fa fa-angle-double-left"></i> Back', ['laporan-bulanan'], ['class' => 'btn btn-default']) ?>
			<!--<?= Html::button('<i class="fa fa-print"></i> Print', ['class' =>'btn btn-success','onClick'=>'print()']) ?> -->
			<?php
			if ((Mimin::checkRoute($this->context->id.'/export-laporan'))){
				$urlGet =  http_build_query(Yii::$app->request->queryParams);
				echo Html::a('<i class="fa fa-table"></i> Ekspor XLS', ['export-laporan?'.$urlGet], ['class'=>'btn btn-success']) ;
			}
			?>
        <?php ActiveForm::end(); ?>
				<h3 class="card-title">
        </h3>
      </div>

      <div class="card-body">
	  				<h4>LAPORAN PEMASOK <?= $model->bulanLabel ?> <?= $model->tahun ?> </h4>
				  <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          [
            'header'=>'Plant',
            'attribute'=>'plant.plant.nama_plant',
            'visible'=>!\Yii::$app->authManager-> getAssignment('plant',\Yii::$app->user->id),
          ],
                    'nama',
                    'telepon',
                    'alamat',
                    [
                    'attribute'=>'provinsi.nama',
                    'label'=>'Provinsi',
                    ],
                    [
                    'attribute'=>'kotaKabupaten.nama',
                    'label'=>'Kota Kabupaten',
                    ],
                    [
                    'attribute'=>'kecamatan.nama',
                    'label'=>'Kecamatan',
                    ],
                    [
                    'attribute'=>'kelurahan.nama',
                    'label'=>'Kelurahan',
                    ],
				],
			]); ?>
