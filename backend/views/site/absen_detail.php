<?php
use yii\helpers\Html;

setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$tanggal = new \DateTime();
$tanggal->setDate($tahun, $bulan, 1);

$sudahBelum = '';
$namaData = '';

if ($status == 1) {
    $sudahBelum = "Sudah Mengisi";
} else {
    $sudahBelum = "Belum Mengisi";
}
if ($jenis == 1) {
    $namaData = 'Data Pembelian';
} elseif ($jenis == 2) {
    $namaData = 'Data Stok';
} else {
    $namaData = 'Data Stok Atau Data Pembelian';
}

$header = $sudahBelum . ' ' . $namaData . ' ';
$this->registerJsFile(
    "@web/js/jquery.PrintArea.js",
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
$this->registerJs(<<<JS
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
JS
);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?= Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                <div class="float-right">
                    <button id="print" class="btn btn-primary" type="button">
                        <span>
                            <i class="fa fa-print"></i>
                            Cetak
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row printableArea">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-center">Daftar Plant Yang
                    <?= $header ?> Pada Bulan
                    <?= $formatter->asDateTime($tanggal->format("Y-m-d"), 'php:F Y'); ?>
                </h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Plant</th>
                                <?php
                                if ($status == 1) {
                                    echo "<th>Tanggal Mengisi</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $absen) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $no ?>
                                    </td>
                                    <td>
                                        <?= $absen['nama'] ?>
                                    </td>
                                    <?php
                                    if ($status == 1) {
                                        $tanggal = $formatter->asDate($absen['tanggal'], 'php:d F Y H:i:s');
                                        echo "<td>" . $tanggal . "</td>";
                                    }
                                    ?>
                                </tr>
                                <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>