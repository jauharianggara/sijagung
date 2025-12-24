<?php
use yii\helpers\Html;

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
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Tahun</th>
                                <th rowspan="2">Bulan</th>
                                <th rowspan="2">Jumlah Plant</th>
                                <th colspan="3">Pembelian</th>
                                <th colspan="3">Stok</th>

                            </tr>
                            <tr>
                                <th>Sudah Mengisi</th>
                                <th>Belum Mengisi</th>

                                <th>Sudah Mengisi</th>
                                <th>Belum Mengisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            //echo json_encode($dataRekapAbsen);
                            
                            foreach ($dataRekapAbsen as $keyTahun => $tahun) {
                                foreach ($tahun as $keyBulan => $absen) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $no ?>
                                        </td>
                                        <td>
                                            <?= $absen['tahun'] ?>
                                        </td>
                                        <td>
                                            <?= $absen['bulan'] ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $absen['plant'] ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            if($absen['pembelian']){
                                                echo Html::a(
                                                    $absen['pembelian'],
                                                    [
                                                        'absen-detail',
                                                        'tahun' => $absen['tahun'],
                                                        'bulan' => $keyBulan,
                                                        'jenis' => 1,
                                                        'status' => 1,
                                                    ],
                                                    [
                                                        'target' => '_blank'
                                                    ]
                                                );
                                            }else{
                                                echo 0;
                                            }
                                            
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php

                                            echo Html::a(
                                                $absen['plant'] - $absen['pembelian'],
                                                [
                                                    'absen-detail',
                                                    'tahun' => $absen['tahun'],
                                                    'bulan' => $keyBulan,
                                                    'jenis' => 1,
                                                    'status' => 0,
                                                ],
                                                [
                                                    'target' => '_blank'
                                                ]
                                            );
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            if($absen['stok']){
                                                echo Html::a(
                                                    $absen['stok'],
                                                    [
                                                        'absen-detail',
                                                        'tahun' => $absen['tahun'],
                                                        'bulan' => $keyBulan,
                                                        'jenis' => 2,
                                                        'status' => 1,
                                                    ],
                                                    [
                                                        'target' => '_blank'
                                                    ]
                                                );
                                            }else{
                                                echo 0;
                                            }
                                            
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            echo Html::a(
                                                $absen['plant'] - $absen['stok'],
                                                [
                                                    'absen-detail',
                                                    'tahun' => $absen['tahun'],
                                                    'bulan' => $keyBulan,
                                                    'jenis' => 2,
                                                    'status' => 0,
                                                ],
                                                [
                                                    'target' => '_blank'
                                                ]
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $no++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>