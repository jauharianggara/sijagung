<?php
use yii\helpers\Html;
use yii\grid\GridView;

setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';
$this->registerJsFile(
    "@web/plugins/Chart.js/Chart.min.js",
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);

$DataMasukBulanIni = backend\models\InputDataPembelian::find()->count();
$DataPendingBulanIni = backend\models\InputDataPembelian::find()->count();
$JumlahPlantTerdata = backend\models\ProfilPlant::find()->count();
$DataPembelian = backend\models\InputDataPembelian::find()->count();
$DataStok = backend\models\InputDataStok::find()->count();

$this->title = 'Dashboard Sistem';

foreach ($dataKonversiVolume14 as $key => $value) {
    $tanggal = new \DateTime($key);
    $bulanPembelian .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $pembelian .= round($value, 0, PHP_ROUND_HALF_UP) . ",";
}

foreach ($dataStok as $key => $value) {
    $tanggal = new \DateTime($key);
    $bulanStok .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $stok .= round($value / 1000, 0, PHP_ROUND_HALF_UP) . ",";
}

foreach ($dataHarga as $item) {
    $tanggal = new \DateTime($item['tanggal']);
    $bulanHarga .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $harga .= round($item['average'], 0, PHP_ROUND_HALF_UP) . ",";
}

foreach ($dataKecukupan as $item) {
    $tanggal = new \DateTime($item['tanggal']);
    $bulanKecukupan .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $kecukupan .= round($item['average'], 0, PHP_ROUND_HALF_UP) . ",";
}

foreach ($dataProyeksi as $key => $value) {
    $tanggal = new \DateTime($key);
    $bulanProyeksi .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $proyeksi .= round($value / 1000, 0, PHP_ROUND_HALF_UP) . ",";
}

foreach ($dataPenggunaan as $item) {
    $tanggal = new \DateTime($item['tanggal']);
    $bulanPenggunaan .= "'" . $formatter->asDateTime($tanggal, 'php:F') . "',";
    $penggunaan .= round($item['penggunaan'] / 1000, 0, PHP_ROUND_HALF_UP) . ",";
}

$bulan1 = (int) date('m', strtotime('-11 months'));
$bulan2 = (int) date('m', strtotime('-10 months'));
$bulan3 = (int) date('m', strtotime('-9 months'));
$bulan4 = (int) date('m', strtotime('-8 months'));
$bulan5 = (int) date('m', strtotime('-7 months'));
$bulan6 = (int) date('m', strtotime('-6 months'));
$bulan7 = (int) date('m', strtotime('-5 months'));
$bulan8 = (int) date('m', strtotime('-4 months'));
$bulan9 = (int) date('m', strtotime('-3 months'));
$bulan10 = (int) date('m', strtotime('-2 months'));
$bulan11 = (int) date('m', strtotime('-1 months'));
$bulan12 = (int) date('m');

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

<?php
$this->registerJs("
	var ctx = document.getElementById('myChart');
	var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
					labels: [" . $bulanPembelian . "],
					datasets: [{
							label: '',
							data: [" . $pembelian . "],
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
									labelString: 'Volume KA 14% (Ton)'
								},
						}],
					}
			}
	});


	var ctx2 = document.getElementById('myChart2');
	var myChart2 = new Chart(ctx2, {
			type: 'horizontalBar',
			data: {
					labels: [" . $bulanStok . "],
					datasets: [{
							label: null,
							data: [" . $stok . "],
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

	var ctx3 = document.getElementById('myChart3');
	var myChart3 = new Chart(ctx3, {
			type: 'bar',
			data: {
					labels: [" . $bulanHarga . "],
					datasets: [{
							label: '',
							data: [" . $harga . "],
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
									labelString: 'Harga KA 14% (Rp/Kg)'
								},
						}],
					}
			}
	});


	var ctx4 = document.getElementById('myChart4');
	var myChart4 = new Chart(ctx4, {
			type: 'horizontalBar',
			data: {
					labels: [" . $bulanKecukupan . "],
					datasets: [{
							label: null,
							data: [" . $kecukupan . "],
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
									labelString: 'Kecukupan Produksi (Hari)'
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

	var ctx5 = document.getElementById('myChart5');
	var myChart5 = new Chart(ctx5, {
			type: 'bar',
			data: {
					labels: [" . $bulanProyeksi . "],
					datasets: [{
							label: '',
							data: [" . $proyeksi . "],
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
									labelString: 'Volume KA 14% (Ton)'
								},
						}],
					}
			}
	});
	");


if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)):
    $this->registerJs("    
        var ctx6 = document.getElementById('myChart6');
        var myChart6 = new Chart(ctx6, {
                type: 'horizontalBar',
                data: {
                        labels: [" . $bulanPenggunaan . "],
                        datasets: [{
                                label: null,
                                data: [" . $penggunaan . "],
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
                                    labelString: 'Penggunaan KA 14%(Ton)'
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
        ");
endif;
?>



<div class="row">

    <!--<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Info Pemeliharan Aplikasi!</h4>
                Untuk peningkatan sistem, kami akan melakukan pemeliharaan aplikasi pada hari Jumat, 9 Agustus pukul 16.00 sd Senin, 12 Agustus pukul 07.00. Selama pemeliharaan, aplikasi sementara tidak akan bisa diakses. Mohon proses input bisa dilakukan sebelum atau setelah masa pemeliharaan. Demikian kami sampaikan. Terima kasih.
              </div>-->

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <?= $this->render('_chart_pembelian', [
            'data' => $dataKonversiVolume14,
            'width' => 100,
            'height' =>100,
        ]) ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Volume Pembelian KA 14% (Ton)</h3>
            </div>

            <div class="card-body">
                <canvas id="myChart" width="100" height="100"></canvas>
            </div>

            <div class="card-footer">

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jumlah Stok (Ton)</h3>
            </div>

            <div class="card-body">
                <canvas id="myChart2" width="100" height="100"></canvas>
                <script>

                </script>
            </div>

            <div class="card-footer">

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Rata - Rata Harga Pembelian KA 14% (Rp/Kg)</h3>
            </div>

            <div class="card-body">
                <canvas id="myChart3" width="100" height="100"></canvas>
            </div>

            <div class="card-footer">

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rata-Rata Kecukupan Produksi (Hari)</h3>
            </div>

            <div class="card-body">
                <canvas id="myChart4" width="100" height="100"></canvas>
                <script>

                </script>
            </div>

            <div class="card-footer">

            </div>
        </div>
    </div>
</div>


<div class="row">
    <?php
    $col = 12;
    $height = 50;
    if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)):
        $col = 6;
        $height = 100;
    endif;
    ?>

    <div class="col-xs-12 col-sm-12 col-md-<?= $col ?> col-lg-<?= $col ?> col-xl-<?= $col ?>">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Proyeksi KA 14%(Ton)</h3>
            </div>

            <div class="card-body">
                <canvas id="myChart5" width="100" height="<?= $height ?>"></canvas>
            </div>

            <div class="card-footer">

            </div>
        </div>
    </div>
    <?php
    if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)):
        ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">Penggunaan KA 14%(Ton)</h3>
                </div>

                <div class="card-body">
                    <canvas id="myChart6" width="100" height="100"></canvas>
                </div>

                <div class="card-footer">

                </div>
            </div>
        </div>
    <?php endif; ?>

</div>


<?php

//echo $this->render('_absen', [
//	'dataAbsen' => $dataAbsen,
//]); 
?>

<?= $this->render('_rekap_absen', [
    'dataRekapAbsen' => $dataRekapAbsen,
]) ?>

<?php
if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
    echo $this->render('_modal');
}
?>