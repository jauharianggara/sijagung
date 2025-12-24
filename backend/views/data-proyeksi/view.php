<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\components\Mimin;
$formatter = \Yii::$app->formatter;

/* @var $this yii\web\View */
/* @var $model backend\models\DataProyeksi */

$this->title = "View Data Proyeksi: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Data Proyeksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="data-proyeksi-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                    <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                  'tahun',
                                  'plant.nama',
                            ]
                    ]) ?>

                    <div class="table-responsive">
                      <table class="table table-bordered" id="data-proyeksi">
                        <thead>
                          <tr>
                           <th class="text-center">No.</th>
                           <th class="text-center">Bulan</th>
                           <th class="text-center">Volume Kebutuhan (Kg)</th>
                           <th class="text-center">Kadar Air(%)</th>
                           <th class="text-center">Volume 14% (Kg)</th>
                           <th class="text-center">Volume 15% (Kg)</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                            $formatter = \Yii::$app->formatter;
                            $formatter->locale = 'id-ID';
                            for ($i = 1; $i <=12 ;$i++) {
                                $datetime = new DateTime();
                                $tanggal = $datetime->createFromFormat('d/m/Y', '1/'.$i.'/22');
                                $monthName = $formatter->asDateTime($tanggal, 'php:F');
                                $item = $data[$i-1];
                    ?>
                            <tr>
                               <td><?= $i?>.</td>
                               <td><?=$monthName?></td>
                               <td class="text-right"><?= $formatter->asDecimal($item->volume,2)?></td>
                               <td class="text-right"><?= $formatter->asDecimal($item->kadar_air_persen,2)?></td>
                               <td class="text-right"><?= $formatter->asDecimal($item->konversiVolume14,2)?></td>
                               <td class="text-right"><?= $formatter->asDecimal($item->konversiVolume15,2)?></td>

                           </tr>
                    <?php
                            }
                          ?>
                        </tbody>

                        <tfoot>

                        </tfoot>
                      </table>
                    </div>
                </div><!-- card-body -->

                <div class="card-footer">
                    <?= Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    <div class="float-right">
                        <?php
                        if ((Mimin::checkRoute($this->context->id . '/update'))) {
                            echo Html::a(
                                '<i class=\'fa fa-edit\'></i> Ubah',
                                ['update',
                                  'tahun'=>$model->tahun,
                                  'id_plant'=>$model->id_plant,
                                ],
                                [
                                    'class' => 'btn waves-effect waves-light btn-primary'
                                ]
                            ) . ' ';
                        }

                        if ((Mimin::checkRoute($this->context->id . '/delete'))) {
                            echo Html::a(
                                '<i class=\'fa fa-trash\'></i> Hapus',
                                [
                                  'delete',
                                  'tahun'=>$model->tahun,
                                  'id_plant'=>$model->id_plant,
                                ],
                                [
                                    'class' => 'btn waves-effect waves-light btn-danger',
                                    'data' => [
                                        'confirm' => 'Apakah anda yakin akan menghapus data ini? data yang sudah dihapus tidak dapat dikembalikan.',
                                        'method' => 'post',
                                    ],
                                ],
                            ) . ' ';
                        }
                        ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
