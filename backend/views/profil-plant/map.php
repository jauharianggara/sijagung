<?php

use yii\helpers\Html;
$this->registerJsFile("@web/plugins/Chart.js/Chart.min.js", [
	'depends' => [\yii\web\JqueryAsset::className()]]
);

$bulan1 =  date('m', strtotime('-2 months'));
$bulan2 = date('m', strtotime('-1 months'));
$bulan3 =  date('m');

$tahun1 = date('Y', strtotime('-2 months'));
$tahun2 = date('Y', strtotime('-1 months'));
$tahun3 = date('Y');

$bulanLabel1 = date('F', strtotime('-2 months'));
$bulanLabel2 = date('F', strtotime('-1 months'));
$bulanLabel3 = date('F');

/* @var $this yii\web\View */
/* @var $model backend\models\Mcif */

$this->title = 'Peta Sebaran';
$this->params['breadcrumbs'][] = ['label' => 'Peta Sebaran'];
$this->params['breadcrumbs'][] = $this->title;

$location = '';
foreach($dataProvider->getModels() AS $item){
  if($item->longitude != '' && $item->latitude != ''){
    $location = $location."['". $item->nama_plant."', ".$item->longitude.", ".$item->latitude.",'".$item->id."','".$item->id_user."'],";
  }
}


$this->registerJsFile("https://maps.google.com/maps/api/js?key=AIzaSyCMSIJLct0nu9ftabXf81gZ_OR4AyNh3yI");

$this->registerJs("
  var locations = [".$location."];
");

$script = <<< JS
var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 4,
    center: new google.maps.LatLng(-2.548926 ,118.0148634 ),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var infowindow = new google.maps.InfoWindow();
  window.onresize = function() {
    var currCenter = map.getCenter();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(currCenter);
  };
  var marker, i;

  for (i = 0; i < locations.length; i++) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map: map
    });

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        var html = "<b>" + locations[i][0] + "</b></br><ul>"+
          "<li><a href='http://simpakan.ditjenpkh.pertanian.go.id/sim1/backend/web/profil-plant/update?id="+locations[i][3]+"'>Profil Plant</a></li>"+
          "<li><a href='http://simpakan.ditjenpkh.pertanian.go.id/sim1/backend/web/input-data-pembelian/index?InputDataPembelianSearch[nama_plant]="+locations[i][3]+"'>Data Pembelian</a></li>"+
          "<li><a href='http://simpakan.ditjenpkh.pertanian.go.id/sim1/backend/web/input-data-stok/index?InputDataStokSearch[nama_plant]="+locations[i][3]+"'>Data Stok</a></li>"+
          "</ul>";
        infowindow.setContent(html);
        infowindow.open(map, marker);
      }
    })(marker, i));
  }

  var ctx = document.getElementById("myChart");
  var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: ["$bulanLabel1", " $bulanLabel2", " $bulanLabel3"],
          datasets: [{
              label: 'Jumlah Pembelian',
              data: [ $pembelian1,  $pembelian2,  $pembelian3],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.9)',
                  'rgba(54, 162, 235, 0.9)',
                  'rgba(255, 206, 86, 0.9)',
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
            xAxes: [{
                scaleLabel: {
                  display: true,
                  labelString: 'Bulan'
                },
            }],
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                scaleLabel: {
                  display: true,
                  labelString: 'Qty Terima (kg)'
                },
            }],
          }
      }
  });


  var ctx2 = document.getElementById("myChart2");
  var myChart2 = new Chart(ctx2, {
      type: 'horizontalBar',
      data: {
          labels: [" $bulanLabel3", " $bulanLabel2", " $bulanLabel1"],
          datasets: [{
              label: 'Jumlah Stok',
              data: [ $stok3,  $stok2,  $stok1],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.9)',
                  'rgba(54, 162, 235, 0.9)',
                  'rgba(255, 206, 86, 0.9)',
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
            xAxes: [{
                scaleLabel: {
                  display: true,
                  labelString: 'Jumlah Stok'
                },
            }],
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },
                scaleLabel: {
                  display: true,
                  labelString: 'Bulan'
                },
            }],
          }
      }
  });
JS;

$this->registerJs($script);
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div id="map" style="width: 1024px; height: 400px;"></div>
	</div>
</div>

<!--
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		<div class="card box-warning">
			<div class="card-header">
			<h3 class="card-title">Grafik Jumlah Pembelian</h3>
			</div>

				<div class="card-body">
					<canvas id="myChart" width="100" height="100"></canvas>
					<script>

						</script>
				</div>

				 <div class="card-footer">

				 </div>
			</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
		<div class="card">
			<div class="card-header">
			<h3 class="card-title">Grafik Jumlah Stok </h3>
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
-->

