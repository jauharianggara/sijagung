<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID.utf8');

$this->title = 'SIJAGUNG 2.0 - Data Pembelian Pakan Jagung Nasional';
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$tanggalAwal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
$tanggalAkhir = new \DateTime($searchModel->tahun_akhir . "-" . $searchModel->bulan_akhir . "-01");

$tahunAwal = date("Y");
$tahunAkhir = 2019;
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
            <img class="image" src="<?= Url::base(true) ?>/landing/jagung-4.jpg" alt="Jagung"
                style="width:100%; height:250px; background-position: center;">
            <div class="carousel-caption ">
                <h2>Data Pembelian</h2>
            </div>
        </div>

        <div class="item blog-header">
            <img class="image" src="<?= Url::base(true) ?>/landing/lahan-jagung-1.jpg" alt="Lahan Jagung"
                style="width:100%; height:250px;">
            <div class="carousel-caption d-none d-md-block">
                <h2>Data Pembelian</h2>
            </div>
        </div>

        <div class="item blog-header">

            <img class="image" src="<?= Url::base(true) ?>/landing/lumbung-jagung-2.jpg" alt="Lumbung Jagung"
                style="width:100%; height:250px;">
            <div class="carousel-caption d-none d-md-block">
                <h2>Data Pembelian</h2>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            echo $form->field($searchModel, 'bulan_awal')->dropDownList(
                                [
                                    "1" => "Januari",
                                    "2" => "Februari",
                                    "3" => "Maret",
                                    "4" => "April",
                                    "5" => "Mei",
                                    "6" => "Juni",
                                    "7" => "Juli",
                                    "8" => "Agustus",
                                    "9" => "September",
                                    "10" => "Oktober",
                                    "11" => "November",
                                    "12" => "Desember",
                                ],
                                [
                                    'id' => 'bulan',
                                    'prompt' => '-- Pilih Bulan --',
                                ]
                            )->label(false);
                            ?>
                        </div>

                        <div class="col-md-2">
                            <?php
                            for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
                                $tahun[$i] = $i;
                            }
                            echo $form->field($searchModel, 'tahun_awal')->dropDownList(
                                $tahun,
                                [
                                    'id' => 'tahun',
                                    'prompt' => '-- Tahun --',
                                ]
                            )->label(false);
                            ?>

                        </div>
                        <div class="col-md-2 text-center">
                            Sampai Dengan
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo $form->field($searchModel, 'bulan_akhir')->dropDownList(
                                [
                                    "1" => "Januari",
                                    "2" => "Februari",
                                    "3" => "Maret",
                                    "4" => "April",
                                    "5" => "Mei",
                                    "6" => "Juni",
                                    "7" => "Juli",
                                    "8" => "Agustus",
                                    "9" => "September",
                                    "10" => "Oktober",
                                    "11" => "November",
                                    "12" => "Desember",
                                ],
                                [
                                    'id' => 'bulan',
                                    'prompt' => '-- Pilih Bulan --',
                                ]
                            )->label(false);
                            ?>
                        </div>

                        <div class="col-md-2">
                            <?php
                            for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
                                $tahun[$i] = $i;
                            }
                            echo $form->field($searchModel, 'tahun_akhir')->dropDownList(
                                $tahun,
                                [
                                    'id' => 'tahun',
                                    'prompt' => '-- Tahun --',
                                ]
                            )->label(false);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-9 col-md-3 text-right">
                            <?php
                            $urlGet = http_build_query(Yii::$app->request->queryParams);
                            echo Html::submitButton('<i class="glyphicon glyphicon-search"></i> Tampil', ['class' => 'btn btn-success']) . ' ';
                            echo Html::a(
                                '<i class=\'glyphicon glyphicon-download\'></i> Export',
                                ['data-pembelian-export?' . $urlGet],
                                [
                                    'class' => 'btn btn-default'
                                ]
                            ) . ' ';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y') . ' Sampai Dengan ' . $formatter->asDateTime($tanggalAkhir, 'php:F') . " " . $tanggalAkhir->format('Y');
                    if ($searchModel->bulan_awal == $searchModel->bulan_akhir && $searchModel->tahun_awal == $searchModel->tahun_akhir) {
                        $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y');
                    }
                    ?>
                    <h3 class="panel-title text-center">Laporan Pembelian Pakan Jagung Nasional<br> Total Volume KA 14%
                        (Ton) Dan Rata-Rata Harga KA 14% (Rp/Kg)<br>
                        <?php echo $periode; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan=3 class='text-center'>No.</th>
                                    <th rowspan=3 class='text-center'>Nama
                                        <?php if ($searchModel->wilayah == 1) {
                                            echo "Pulau";
                                        } else {
                                            echo "Provinsi";
                                        } ?>
                                    </th>
                                    <?php
                                    for ($tahun = $searchModel->tahun_awal; $tahun <= $searchModel->tahun_akhir; $tahun++) {
                                        $colSpan = 12 * 2;
                                        if ($tahun == $searchModel->tahun_awal) {
                                            $colSpan = (13 - $searchModel->bulan_awal) * 2;
                                        }
                                        if ($tahun == $searchModel->tahun_akhir) {
                                            $colSpan = $searchModel->bulan_akhir * 2;
                                        }
                                        echo "<th colspan=" . $colSpan . " class='text-center'>" . $tahun . "</th>";
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    $interval = $tanggalAwal->diff($tanggalAkhir);
                                    $intervalBulan = $interval->m + ($interval->y * 12);
                                    $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                                    for ($i = 0; $i <= $intervalBulan; $i++) {
                                        echo "<th colspan=2 class='text-center'>" . $formatter->asDateTime($tanggal, 'php:F') . "</th>";
                                        $tanggal->modify('+1 month');
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    $tanggalAwal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                                    $tanggalAkhir = new \DateTime($searchModel->tahun_akhir . "-" . $searchModel->bulan_akhir . "-01");
                                    $interval = $tanggalAwal->diff($tanggalAkhir);
                                    $intervalBulan = $interval->m + ($interval->y * 12);
                                    $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                                    for ($i = 0; $i <= $intervalBulan; $i++) {
                                        echo "<th class='text-center'>Volume</th>";
                                        echo "<th class='text-center'>Harga</th>";
                                        $tanggal->modify('+1 month');
                                    }
                                    ?>
                                </tr>

                            </thead>

                            <tbody>
                                <?php
                                foreach ($dataProvider as $pulau):
                                    echo "<tr>";
                                    echo    '<th colspan="2">';
                                    echo        $pulau['nama'];
                                    echo    '</th>';
                                        $interval = $tanggalAwal->diff($tanggalAkhir);
                                        $intervalBulan = $interval->m + ($interval->y * 12);
                                        $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                                        for ($i = 0; $i <= $intervalBulan; $i++):
                                            //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                                            $tahun = $tanggal->format('Y');
                                            $bulan = (int) $tanggal->format('m');
                                            echo '<th class="text-right">';
                                                if (isset($pulau['tahun'][$tahun]['bulan'][$bulan]['volume'])) {
                                                    echo $formatter->asDecimal($pulau['tahun'][$tahun]['bulan'][$bulan]['volume'], 2);
                                                } else {
                                                    echo 0;
                                                }
                                            echo '</th>';
                                            echo '<th class="text-right">';
                                                ///echo $tahun.'  '.$bulan.' ';
                                                if (isset($pulau['tahun'][$tahun]['bulan'][$bulan]['harga'])) {
                                                    //echo $formatter->asDecimal( $itemBulan['harga'],0).' '.$itemBulan['harga_semua'].' '.$itemBulan['count'];
                                                    echo $formatter->asDecimal($pulau['tahun'][$tahun]['bulan'][$bulan]['harga'], 0);
                                                } else {
                                                    echo 0;
                                                }
                                            echo '</th>';
                                            $summary[$tahun][$bulan]['volume'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['volume'];
                                            $summary[$tahun][$bulan]['harga'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['harga_semua'];
                                            $summary[$tahun][$bulan]['count'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['count'];

                                            //echo $formatter->asDateTime($tanggal, 'php:m Y') . " - ";
                                            $tanggal->modify('+1 month');
                                        endfor;
                                        
                                        echo "</tr>";

                                        $no = 1;
                                        foreach ($pulau['provinsi'] as $provinsi):
                                            echo "<tr>";
                                            echo    '<td>';
                                            echo        $no;
                                            echo    '</td>';
                                            echo    '<td>';
                                            echo        $provinsi['nama'];
                                            echo    '</td>';
                                                $interval = $tanggalAwal->diff($tanggalAkhir);
                                                $intervalBulan = $interval->m + ($interval->y * 12);
                                                $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                                                for ($i = 0; $i <= $intervalBulan; $i++):
                                                    //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                                                    $tahun = $tanggal->format('Y');
                                                    $bulan = (int) $tanggal->format('m');
                                                    echo '<td class="text-right">';
                                                        if (isset($provinsi['tahun'][$tahun]['bulan'][$bulan]['volume'])) {
                                                            echo $formatter->asDecimal($provinsi['tahun'][$tahun]['bulan'][$bulan]['volume'], 2);
                                                        } else {
                                                            echo 0;
                                                        }
                                                    echo '</td>';
                                                    echo '<td class="text-right">';
                                                        ///echo $tahun.'  '.$bulan.' ';
                                                        if (isset($provinsi['tahun'][$tahun]['bulan'][$bulan]['harga'])) {
                                                            //echo $formatter->asDecimal( $itemBulan['harga'],0).' '.$itemBulan['harga_semua'].' '.$itemBulan['count'];
                                                            echo $formatter->asDecimal($provinsi['tahun'][$tahun]['bulan'][$bulan]['harga'], 0);
                                                        } else {
                                                            echo 0;
                                                        }
                                                    echo '</td>';
                                                    //$summary[$tahun][$bulan]['volume'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['volume'];
                                                    //$summary[$tahun][$bulan]['harga'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['harga_semua'];
                                                    //$summary[$tahun][$bulan]['count'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['count'];

                                                    //echo $formatter->asDateTime($tanggal, 'php:m Y') . " - ";
                                                    $tanggal->modify('+1 month');
                                                    
                                                endfor;
                                                $no++;
                                                echo "</tr>";
                                        endforeach;
                                endforeach;
                                ?>
                            </tbody>
                            <tfooter>
                                <?php
                                $no = 1;
                                ?>
                                <tr>
                                    <td colspan=2>

                                    </td>
                                    <?php
                                    foreach ($summary as $tahun => $itemTahun):
                                        foreach ($itemTahun as $bulan => $itemBulan):
                                            ?>
                                            <td class="text-right">
                                                <?php
                                                    echo $formatter->asDecimal($itemBulan['volume'], 2);
                                                ?>
                                            </td>
                                            <td class="text-right">
                                                <?php
                                                    echo $formatter->asDecimal($itemBulan['harga'] / $itemBulan['count'], 0);
                                                ?>
                                            </td>
                                            <?php
                                        endforeach;
                                    endforeach;
                                    ?>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>