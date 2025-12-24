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
/* @var $this yii\web\View */
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <?= $this->render('_chart_realisasi', [
            'dataVolume' => $dataVolumeTahunIni,
            'dataProyeksi' => $dataProyeksiTahunIni,
            'width' => 100,
            'height' => 40,
        ]) ?>
    </div>
</div>
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
            'height' => 100,
        ]) ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <?= $this->render('_chart_stok', [
            'data' => $dataStok,
            'width' => 100,
            'height' => 100,
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <?= $this->render('_chart_harga', [
            'data' => $dataHarga,
            'width' => 100,
            'height' => 100,
        ]) ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <?= $this->render('_chart_kecukupan', [
            'data' => $dataKecukupan,
            'width' => 100,
            'height' => 100,
        ]) ?>
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
        <?= $this->render('_chart_proyeksi', [
            'data' => $dataProyeksi,
            'width' => 100,
            'height' => $height,
        ]) ?>
    </div>
    <?php
    if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)):
        ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <?= $this->render('_chart_penggunaan', [
                'data' => $dataPenggunaan,
                'width' => 100,
                'height' => 100,
            ]) ?>
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