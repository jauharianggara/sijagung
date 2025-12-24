<?php
setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;
$formatter->locale = 'id-ID';

$tanggalAwal = new \DateTime();
$tanggalAwal->modify('-12 month');
$tanggalAwal->setDate($tanggalAwal->format('Y'), $tanggalAwal->format('m'), 1);

$tanggalAkhir = new \DateTime();
//$tanggalAkhir->modify('-1 month');
$tanggalAkhir->setDate($tanggalAkhir->format('Y'), $tanggalAkhir->format('m'), 1);

$interval = new \DateInterval('P1M');
$period = new \DatePeriod($tanggalAwal, $interval, $tanggalAkhir);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rekapitulasi Laporan Plant</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Nama Plant</th>
                                <?php
                                foreach ($period as $dt) {
                                    echo "<th colspan=2>" . $formatter->asDateTime($dt, 'php:F') . "</th>";
                                }
                                ?>
                            </tr>
                            <tr>
                                <?php
                                foreach ($period as $dt) {
                                    echo "<th>P</th>";
                                    echo "<th>S</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($dataAbsen as $absen) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $no ?>
                                    </td>
                                    <td>
                                        <?= $absen['nama'] ?>
                                    </td>
                                    <?php
                                    foreach ($absen['data'] as $tahun => $dataTahun) {
                                        foreach ($dataTahun as $bulan => $dataBulan) {
                                            echo "<td>";
                                            if ($dataBulan['pembelian'] == 1) {
                                                echo '<span class="text-success"><strong>&#10003;</strong></span>';
                                            } else {
                                                echo '<span class="text-danger"><strong>&#10005;</strong></span>';
                                            }
                                            echo "</td>";

                                            echo "<td>";
                                            if ($dataBulan['stok'] == 1) {
                                                echo '<span class="text-success"><strong>&#10003;</strong></span>';
                                            } else {
                                                echo '<span class="text-danger"><strong>&#10005;</strong></span>';
                                            }
                                            echo "</td>";
                                        }
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