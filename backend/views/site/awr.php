<?php
use  yii\helpers\Html;
use yii\grid\GridView;

$DataMasukBulanIni  = backend\models\InputDataPembelian::find()->count();
$DataPendingBulanIni  = backend\models\InputDataPembelian::find()->count();
$JumlahPlantTerdata  = backend\models\ProfilPlant::find()->count();
$DataPembelian  = backend\models\InputDataPembelian::find()->count();
$DataStok  = backend\models\InputDataStok::find()->count();

$this->title = 'Dashboard Sistem';



$bulan1 = (int)date('m', strtotime('-11 months'));
$bulan2 = (int)date('m', strtotime('-10 months'));
$bulan3 = (int)date('m', strtotime('-9 months'));
$bulan4 = (int)date('m', strtotime('-8 months'));
$bulan5 = (int)date('m', strtotime('-7 months'));
$bulan6 = (int)date('m', strtotime('-6 months'));
$bulan7 = (int)date('m', strtotime('-5 months'));
$bulan8 = (int)date('m', strtotime('-4 months'));
$bulan9 = (int)date('m', strtotime('-3 months'));
$bulan10 = (int)date('m', strtotime('-2 months'));
$bulan11 = (int)date('m', strtotime('-1 months'));
$bulan12 = (int)date('m');

$tahun1 = date('Y', strtotime('-11 months'));
$tahun2 = date('Y', strtotime('-10 months'));
$tahun3 = date('Y', strtotime('-9 months'));
$tahun4 = date('Y', strtotime('-8 months'));
$tahun5 = date('Y', strtotime('-7 months'));
$tahun6 = date('Y', strtotime('-6 months'));
$tahun7 = date('Y', strtotime('-5 months'));
$tahun8 = date('Y', strtotime('-4 months'));
$tahun9 = date('Y', strtotime('-3 months'));
$tahun10 = date('Y', strtotime('-2 months'));
$tahun11 = date('Y', strtotime('-1 months'));
$tahun12 = date('Y');

$bulanLabel1 = date('F', strtotime('-11 months'));
$bulanLabel2 = date('F', strtotime('-10 months'));
$bulanLabel3 = date('F', strtotime('-9 months'));
$bulanLabel4 = date('F', strtotime('-8 months'));
$bulanLabel5 = date('F', strtotime('-7 months'));
$bulanLabel6 = date('F', strtotime('-6 months'));
$bulanLabel7 = date('F', strtotime('-5 months'));
$bulanLabel8 = date('F', strtotime('-4 months'));
$bulanLabel9 = date('F', strtotime('-3 months'));
$bulanLabel10 = date('F', strtotime('-2 months'));
$bulanLabel11 = date('F', strtotime('-1 months'));
$bulanLabel12 = date('F');

/* @var $this yii\web\View */
?>



<div class="row">

			<!--<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Info Pemeliharan Aplikasi!</h4>
                Untuk peningkatan sistem, kami akan melakukan pemeliharaan aplikasi pada hari Jumat, 9 Agustus pukul 16.00 sd Senin, 12 Agustus pukul 07.00. Selama pemeliharaan, aplikasi sementara tidak akan bisa diakses. Mohon proses input bisa dilakukan sebelum atau setelah masa pemeliharaan. Demikian kami sampaikan. Terima kasih.
              </div>-->

	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		<div class="box box-warning">
			<div class="box-header with-border ">
			<h3 class="box-title">Grafik Jumlah Pembelian (Dalam Satuan Ton)</h3>
				<div class="box-tools pull-right">

				</div>
			</div>

				<div class="box-body">
					<canvas id="myChart" width="100" height="100"></canvas>
					<script>
						var ctx = document.getElementById("myChart");
						var myChart = new Chart(ctx, {
						    type: 'bar',
						    data: {
						        labels: ["<?= $bulanLabel1?>", "<?= $bulanLabel2?>", "<?= $bulanLabel3?>", "<?= $bulanLabel4?>", "<?= $bulanLabel5?>", "<?= $bulanLabel6?>", "<?= $bulanLabel7?>", "<?= $bulanLabel8?>", "<?= $bulanLabel9?>", "<?= $bulanLabel10?>", "<?= $bulanLabel11?>", "<?= $bulanLabel12?>"],
						        datasets: [{
						            label: '',
						            data: [<?= $pembelian1?>, <?= $pembelian2?>, <?= $pembelian3?>,<?= $pembelian4?>,<?= $pembelian5?>, <?= $pembelian6?>, <?= $pembelian7?>, <?= $pembelian8?>, <?= $pembelian9?>, <?= $pembelian10?>, <?= $pembelian11?>, <?= $pembelian12?>],
						            backgroundColor: [
						                'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						                'rgba(255, 206, 86, 0.9)',
										'rgba(255, 206, 162, 0.9)',
										'rgba(255, 206, 132, 0.9)',
										'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						                'rgba(255, 206, 86, 0.9)',
										'rgba(255, 206, 162, 0.9)',
										'rgba(255, 206, 132, 0.9)',
										'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						            ],
						            borderColor: [
						                'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',
						                'rgba(255, 206, 86, 1)',
										'rgba(255, 206, 162, 1)',
										'rgba(255, 206, 132, 1)',
										'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',
						                'rgba(255, 206, 86, 1)',
										'rgba(255, 206, 162, 1)',
										'rgba(255, 206, 132, 1)',
										'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',

						            ],
						            borderWidth: 1
						        }]
						    },
						    options: {
										legend: {
											display: false
										},
						        scales: {
											xAxes: [{
													scaleLabel: {
														display: true,
														labelString: 'Bulan'
													},
											}],
											yAxes: [{
													ticks: {
															beginAtZero:true,
															userCallback: function(value, index, values) {
											            // Convert the number to a string and splite the string every 3 charaters from the end
											            value = value.toString();
											            value = value.split(/(?=(?:...)*$)/);

											            // Convert the array to a string and format the output
											            value = value.join('.');
											            return value;
											        }
													},
													scaleLabel: {
														display: true,
														labelString: 'Qty Terima (Ton)'
													},
											}],
						        }
						    }
						});
						</script>
				</div>

				 <div class="box-footer">

				 </div>
			</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		<div class="box box-danger">
			<div class="box-header with-border ">
			<h3 class="box-title">Grafik Jumlah Stok (Dalam Satuan Ton) </h3>
				<div class="box-tools pull-right">

				</div>
			</div>

				<div class="box-body">
					<canvas id="myChart2" width="100" height="100"></canvas>
					<script>
						var ctx = document.getElementById("myChart2");
						var myChart = new Chart(ctx, {
						    type: 'horizontalBar',
						    data: {
						        labels: ["<?= $bulanLabel12?>","<?= $bulanLabel11?>","<?= $bulanLabel10?>", "<?= $bulanLabel9?>", "<?= $bulanLabel8?>", "<?= $bulanLabel7?>", "<?= $bulanLabel6?>", "<?= $bulanLabel5?>", "<?= $bulanLabel4?>", "<?= $bulanLabel3?>", "<?= $bulanLabel2?>", "<?= $bulanLabel1?>"],
						        datasets: [{
						            label: null,
						            data: [<?= $stok12?>, <?= $stok11?>, <?= $stok10?>, <?= $stok9?>, <?= $stok8?>, <?= $stok7?>, <?= $stok6?>, <?= $stok5?>,<?= $stok4?>,<?= $stok3?>, <?= $stok2?>, <?= $stok1?>],
						            backgroundColor: [
						                'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						                'rgba(255, 206, 86, 0.9)',
										'rgba(255, 206, 162, 0.9)',
										'rgba(255, 206, 132, 0.9)',
										'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						                'rgba(255, 206, 86, 0.9)',
										'rgba(255, 206, 162, 0.9)',
										'rgba(255, 206, 132, 0.9)',
										'rgba(255, 99, 132, 0.9)',
						                'rgba(54, 162, 235, 0.9)',
						            ],
						            borderColor: [
						                'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',
						                'rgba(255, 206, 86, 1)',
										'rgba(255, 206, 162, 1)',
										'rgba(255, 206, 132, 1)',
										'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',
						                'rgba(255, 206, 86, 1)',
										'rgba(255, 206, 162, 1)',
										'rgba(255, 206, 132, 1)',
										'rgba(255,99,132,1)',
						                'rgba(54, 162, 235, 1)',
						            ],
						            borderWidth: 1
						        }]
						    },
						    options: {
										legend: {
											display: false
										},
						        scales: {
											xAxes: [{
													scaleLabel: {
														display: true,
														labelString: 'Jumlah Stok'
													},
													ticks: {
															beginAtZero:true,
															userCallback: function(value, index, values) {
											            // Convert the number to a string and splite the string every 3 charaters from the end
											            value = value.toString();
											            value = value.split(/(?=(?:...)*$)/);

											            // Convert the array to a string and format the output
											            value = value.join('.');
											            return value;
											        }
													},
											}],
											yAxes: [{

													scaleLabel: {
														display: true,
														labelString: 'Bulan'
													},
											}],
						        }
						    }
						});
						</script>
				</div>

				 <div class="box-footer">

				 </div>
			</div>
	</div>
</div>
