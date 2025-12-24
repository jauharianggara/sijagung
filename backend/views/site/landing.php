<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$this->title = 'SIJAGUNG 2.0';
?>

<?php
?>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active blog-header">
            <img class="image" src="<?= Url::base(true) ?>/landing/lumbung-jagung-2.jpg" alt="Jagung"
                style="width:100%; height:500px; background-position: center;">
            <div class="carousel-caption ">
                <img class="image-responsive text-center" src="<?= Url::base(true) ?>/logo/logo.png"
                    style="max-width:128px;">
                <h2>Sistem Informasi Pelaporan Serapan Jagung Lokal Secara Online (SIJAGUNG) Versi 2.0</h2>
                <p>Sistem ini dikembangkan sejak tahun 2019 oleh Direktorat Pakan Direktorat Jenderal Peternakan dan
                    Kesehatan Hewan. <br>Tujuan sistem ini untuk memantau pembelian, harga dan stok jagung serta
                    kecukupan produksi.</p>
            </div>
        </div>

        <div class="item blog-header">
            <img class="image" src="<?= Url::base(true) ?>/landing/lahan-jagung-1.jpg" alt="Lahan Jagung"
                style="width:100%; height:500px;">
            <div class="carousel-caption d-none d-md-block">
                <img class="image-responsive text-center" src="<?= Url::base(true) ?>/logo/logo.png"
                    style="max-width:128px;">
                <h2>Sistem Informasi Pelaporan Serapan Jagung Lokal Secara Online (SIJAGUNG) Versi 2.0</h2>
                <p>Sistem ini dikembangkan sejak tahun 2019 oleh Direktorat Pakan Direktorat Jenderal Peternakan dan
                    Kesehatan Hewan. <br>Tujuan sistem ini untuk memantau pembelian, harga dan stok jagung serta
                    kecukupan produksi.</p>
            </div>
        </div>

        <div class="item blog-header">

            <img class="image" src="<?= Url::base(true) ?>/landing/jagung-4.jpg" alt="Lumbung Jagung"
                style="width:100%; height:500px;">
            <div class="carousel-caption d-none d-md-block">
                <img class="image-responsive text-center" src="<?= Url::base(true) ?>/logo/logo.png"
                    style="max-width:128px;">
                <h2>Sistem Informasi Pelaporan Serapan Jagung Lokal Secara Online (SIJAGUNG) Versi 2.0</h2>
                <p>Sistem ini dikembangkan sejak tahun 2019 oleh Direktorat Pakan Direktorat Jenderal Peternakan dan
                    Kesehatan Hewan. <br> Tujuan sistem ini untuk memantau pembelian, harga dan stok jagung serta
                    kecukupan produksi.</p>
            </div>
        </div>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?= $this->render('_chart_realisasi', [
                'dataVolume' => $dataVolumeTahunIni,
                'dataProyeksi' => $dataProyeksiTahunIni,
                'width' => 100,
                'height' => 30,
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
                'height' => 50,
            ]) ?>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <?= $this->render('_chart_stok', [
                'data' => $dataStok,
                'width' => 100,
                'height' => 50,
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <?= $this->render('_chart_harga', [
                'data' => $dataHarga,
                'width' => 100,
                'height' => 50,
            ]) ?>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <?= $this->render('_chart_kecukupan', [
                'data' => $dataKecukupan,
                'width' => 100,
                'height' => 50,
            ]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?= $this->render('_chart_proyeksi', [
                'data' => $dataProyeksi,
                'width' => 100,
                'height' => 30,
            ]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <?php
            foreach ($berita as $item) {
                ?>
                <!-- Post -->
                <div class="panel panel-info">
                    <div class="panel-heading with-border ">
                        <h4>
                            Berita
                        </h4>
                    </div>


                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="post">
                                    <div class="user-block" style="margin:25px">
                                        <span class="username">
                                            <h4>
                                                <?= $item->judul ?>
                                            </h4>
                                            <a href="#" class="pull-right btn-box-tool"></a>
                                        </span>
                                        <span class="description">
                                            <?= $item->dateAdd ?>
                                        </span>
                                    </div>

                                    <!-- /.user-block -->
                                    <div class="row" style="margin:10px">
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                                            <?= $item->isi ?>
                                        </div>
                                    </div>

                                    <div class="row" style="margin:10px">
                                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                            <img class="img-responsive"
                                                src="<?= \yii\helpers\Url::base(true) . $item->imageUpload ?>" alt="" ">
                                                          </div>
                                                        </div>

                                                        <?php
                                                        if ($item->fileUpload != '') {
                                                            ?>
                                                                                  <div class=" row" style="margin:10px">
                                                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                                                    <a href="<?= \yii\helpers\Url::base(true) . $item->fileUpload ?>"
                                                        alt="" " class=" btn btn-primary"><i
                                                            class="glyphicon glyphicon-download"></i> Download File</a>
                                                </div>
                                            </div>
                                            <?php
                                                        }
                                                        ?>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.post -->
                    <?php
            }
            ?>

            </div>
        </div>
    </div>
</div>