<?php
setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$dataRealisasi = [];
$volume ='';
$proyeksi = '';

//echo json_encode($dataVolume);
//echo '<br>';
//echo json_encode($dataProyeksi);
//echo '<br>';

foreach ($dataVolume as $key => $value) {
	$dataRealisasi[$key]['volume'] = $value;
}

foreach ($dataProyeksi as $key => $value) {
    $dataRealisasi[$key]['realisasi'] = 0;
	$dataRealisasi[$key]['proyeksi'] =  $value;
    $tanggal = new \DateTime( $key);
	$bulan .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
}

//echo json_encode($dataRealisasi);

foreach ($dataRealisasi as $key => $value) {
	$valVolume = $value['volume']?$value['volume']:0;
	$valProyeksi = $value['proyeksi']?$value['proyeksi']/1000:0;
	$hasil = 0;
	if( $valVolume > 0 && $valProyeksi > 0){
		$hasil = ($valVolume/$valProyeksi)*100;
	}

    $volume .= round($valVolume, 0, PHP_ROUND_HALF_UP) . ",";
	$proyeksi .= round($valProyeksi, 0, PHP_ROUND_HALF_UP) . ",";
	$persentase .= $hasil. ",";
}

$this->registerJs(<<<JS
    var ctRealisasi = document.getElementById('chartRealisasi');
	var chartRealisasi = new Chart(ctRealisasi, {
			type: 'bar',
			data:{
					datasets: [
					{
						type: 'line',
						yAxisID: 'persentase',
						label: 'Persentase',
						data: [$persentase],
						borderColor: 'rgba(255, 172, 28, 1)',
						fill: false,
						borderWidth: 3
					},
                    {
							type: 'bar',
							label: 'Proyeksi',
							yAxisID: 'realisasi',
							data: [$proyeksi],
							backgroundColor: 'rgba(0, 250, 0, 0.9)',
							borderColor: 'rgba(0, 250, 0, 1)',
							borderWidth: 1
					},
					{
							type: 'bar',
							label: 'Realisasi',
							yAxisID: 'realisasi',
							data: [$volume],
							backgroundColor: 'rgba(0, 100, 0, 0.9)',
							borderColor: 'rgba(0, 100, 0, 1)',
							borderWidth: 1
					},
					
					
					
				],
				labels: [$bulan],
			},
			options: {
					legend: {
						display: true
					},
                    tooltips: {
						callbacks: {
							label: function(tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
			
								if (label) {
									label += ': ';
								}
								// Convert the number to a string and splite the string every 3 charaters from the end
								label = parseInt(tooltipItem.yLabel).toString();
								label = label.split(/(?=(?:...)*$)/);
								// Convert the array to a string and format the output
								label = label.join('.');
								return ''+label;
							}
						}
					},
					scales: {
						xAxes: [{
								scaleLabel: {
									display: true,
									labelString: 'Bulan'
								},
						}],
						yAxes: [{
								id: 'persentase',
								position: 'right',
								ticks: {
										beginAtZero:true,
										userCallback: function(value, index, values) {
												// Convert the number to a string and splite the string every 3 charaters from the end
												value = value.toString();
												value = value.split(/(?=(?:...)*$)/);
												// Convert the array to a string and format the output
												value = value.join('.');
												return value +' %';
										}
								},
								scaleLabel: {
									display: true,
									labelString: 'Presentase Realisasi'
								},
						},
						{
								id: 'realisasi',
								position: 'left',
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
									labelString: 'Volume Ka 14%'
								},
						},
					],
					}
			}
	});
JS
);
$tanggal = new DateTime();
$tanggal->modify('-2 month');
?>

<div class="card">
    <div class="card-header ">
        <h3 class="card-title">Realisasi Volume Pembelian KA 14% Tahun <?= $tanggal->format('Y')?></h3>
    </div>

    <div class="card-body">
        <canvas id="chartRealisasi" width="<?= $width ?>" height="<?= $height ?>"></canvas>
    </div>

    <div class="card-footer">

    </div>
</div>