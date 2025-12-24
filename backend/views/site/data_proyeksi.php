<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_ALL, 'id_ID.utf8');

$this->title = 'SIJAGUNG 2.0 - Data Proyeksi Pakan Jagung Nasional';
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$tanggalAwal = new \DateTime($searchModel->tahun . "-01-01");
$tanggalAkhir = new \DateTime($searchModel->tahun . "-12-01");

$tahunAwal = date("Y") + 1;
$tahunAkhir = 2023;
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
                <h2>Data Proyeksi Kebutuhan Jagung Industri Pakan Kadar Air 14% (Ton)</h2>
            </div>
        </div>

        <div class="item blog-header">
            <img class="image" src="<?= Url::base(true) ?>/landing/lahan-jagung-1.jpg" alt="Lahan Jagung"
                style="width:100%; height:250px;">
            <div class="carousel-caption d-none d-md-block">
                <h2>Data Proyeksi Kebutuhan Jagung Industri Pakan Kadar Air 14% (Ton)</h2>
            </div>
        </div>

        <div class="item blog-header">

            <img class="image" src="<?= Url::base(true) ?>/landing/lumbung-jagung-2.jpg" alt="Lumbung Jagung"
                style="width:100%; height:250px;">
            <div class="carousel-caption d-none d-md-block">
                <h2>Data Proyeksi Kebutuhan Jagung Industri Pakan Kadar Air 14% (Ton)</h2>
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
                    <div class="col-md-3">
                        <?php
                        for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
                            $tahun[$i] = $i;
                        }
                        echo $form->field($searchModel, 'tahun')->dropDownList(
                            $tahun,
                            [
                                'id' => 'tahun',
                                'prompt' => '-- Tahun --',
                            ]
                        )->label(false);
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        $urlGet = http_build_query(Yii::$app->request->queryParams);
                        echo Html::submitButton('<i class="glyphicon glyphicon-search"></i> Tampil', ['class' => 'btn btn-success']) . ' ';
                        echo Html::a(
                            '<i class=\'glyphicon glyphicon-download\'></i> Export',
                            ['data-proyeksi-export?' . $urlGet],
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
    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">Laporan Proyeksi Pakan Jagung Nasional<br>Kadar Air 14%
                        (Ton)<br>Tahun
                        <?php echo $searchModel->tahun; ?>
                    </h3>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table  table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">
                                        Nama
                                        <?php
                                        if ($searchModel->wilayah == 1) {
                                            echo "Pulau";
                                        } elseif ($searchModel->wilayah == 2 || $searchModel->wilayah == 3) {
                                            echo "Provinsi";
                                        } else {
                                            echo "Plant";
                                        }
                                        ?>
                                    </th>
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        $tanggal = new \DateTime($searchModel->tahun . "-" . $i . "-01");
                                        $monthName = $formatter->asDateTime($tanggal, 'php:F'); // March
                                        echo '<th  class="text-center">' . $monthName . '</th>';
                                    }
                                    ?>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $noPulau = 1;
                                foreach ($dataProvider as $pulau):
                                    $totalBulananPulau = 0;
                                    echo '<tr >';
                                    echo '<th colspan="2">' . $pulau['nama'] . '</th>';
                                    for ($i = 1; $i <= 12; $i++):
                                        if ($pulau['bulan'][$i]['volume']) {
                                            echo '<th  class="text-right">' . $formatter->asDecimal($pulau['bulan'][$i]['volume'], 2) . '</td>';
                                            $summary[$i] += $pulau['bulan'][$i]['volume'];
                                            $totalBulananPulau += $pulau['bulan'][$i]['volume'];
                                        } else {
                                            echo '<th  class="text-right">0</th>';
                                        }
                                    endfor;
                                    echo '<th  class="text-right">' . $formatter->asDecimal($totalBulananPulau, 2) . '</td>';
                                    echo '</tr>';

                                    $noProvinsi = 1;
                                    foreach ($pulau['provinsi'] as $provinsi) {
                                        $totalBulananProvinsi = 0;
                                        echo '<tr >';
                                        echo '<td >' . $noProvinsi . '</td>';
                                        echo '<td>' . $provinsi['nama'] . '</td>';
                                        for ($i = 1; $i <= 12; $i++):
                                            if ($provinsi['bulan'][$i]['volume']) {
                                                echo '<td  class="text-right">' . $formatter->asDecimal($provinsi['bulan'][$i]['volume'], 2) . '</td>';
                                                $totalBulananProvinsi += $provinsi['bulan'][$i]['volume'];
                                            } else {
                                                echo '<td  class="text-right">0</td>';
                                            }
                                        endfor;
                                        echo '<td  class="text-right">' . $formatter->asDecimal($totalBulananProvinsi, 2) . '</td>';
                                        echo '</tr>';
                                        $noProvinsi++;
                                    }
                                    echo '<tr><td></td></tr>';
                                    $noPulau++;
                                endforeach;
                                ?>
                            </tbody>
                            <tfooter>
                                <?php
                                $no = 1;
                                ?>
                                <tr>
                                    <th colspan=2>
                                        Total
                                    </th>
                                    <?php
                                    $totalAll = 0;
                                    foreach ($summary as $item):
                                        ?>
                                        <th class="text-right">
                                            <?php
                                            echo $formatter->asDecimal($item, 2);
                                            $totalAll += $item;
                                            ?>
                                        </th>
                                        <?php
                                    endforeach;
                                    ?>
                                    <th class="text-right">
                                        <?= $formatter->asDecimal($totalAll, 2) ?>
                                    </th>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>