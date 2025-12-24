<?php
setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

foreach ($data as $key => $value) {
    $tanggal = new \DateTime($key);
    $bulan .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $jumlah .= round($value / 1000, 0, PHP_ROUND_HALF_UP) . ",";
}

$this->registerJs(<<<JS
    var ctxStok = document.getElementById('chartStok');
	var chartStok = new Chart(ctxStok, {
			type: 'horizontalBar',
			data: {
					labels: [$bulan],
					datasets: [{
							label: null,
							data: [$jumlah],
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
                    tooltips: {
						callbacks: {
							label: function(tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';
			
								if (label) {
									label += ': ';
								}
								// Convert the number to a string and splite the string every 3 charaters from the end
								label = parseInt(tooltipItem.xLabel).toString();
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
									labelString: 'Jumlah Stok (Ton)'
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

JS
);
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jumlah Stok (Ton)</h3>
    </div>

    <div class="card-body">
        <canvas id="chartStok" width="<?= $width ?>" height="<?= $height ?>"></canvas>
        <script>

        </script>
    </div>

    <div class="card-footer">

    </div>
</div>