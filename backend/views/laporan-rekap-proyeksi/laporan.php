<?php

use yii\helpers\Html;
use yii\grid\GridView;
use hscstudio\mimin\components\Mimin;
use yii\helpers\ArrayHelper;
use backend\models\InputDataStok;
use backend\models\Provinsi;
use backend\models\ProfilPlant;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

setlocale(LC_TIME, 'id_ID.UTF-8');
$formatter = \Yii::$app->formatter;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InputDataStokSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

  if($searchModel->wilayah == 1){
    $this->title = "Laporan Proyeksi Nasional Tahun ".$searchModel->tahun;
  }elseif($searchModel->wilayah == 2){
    $this->title = "Laporan Proyeksi Provinsi Tahun ".$searchModel->tahun;
  }elseif($searchModel->wilayah == 3){
    $this->title = "Laporan Proyeksi Provinsi Gabungan Tahun ".$searchModel->tahun;
  }else{
    $this->title = "Laporan Proyeksi Plant Tahun ".$searchModel->tahun;
  }

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-data-stok-index">

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <?php
                $urlGet =  http_build_query(Yii::$app->request->queryParams);
                echo Html::a(
                    '<i class=\'fa fa-download\'></i> Export',
                    ['export?' . $urlGet],
                    [
                        'class' => 'btn btn-primary'
                    ]
                ) . ' ';
              ?>
            </div><!-- card-header -->
            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>
                          Nama
                          <?php
                            if($searchModel->wilayah == 1){
                              echo "Pulau";
                            }elseif($searchModel->wilayah == 2){
                              echo "Provinsi";
                            }elseif($searchModel->wilayah == 3){
                                echo "Provinsi";
                            }else{
                              echo "Plant";
                            }
                          ?>
                        </th>
                        <?php
                          for($i = 1; $i <= 12 ; $i++){
                            $monthName = strftime('%B', mktime(0, 0, 0, $i)); // March
                            echo '<th>'.$monthName.'</th>';
                          }
                        ?>
                        <th>Total</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        $no =1;
                        $totalBulan = [];
                        foreach ($dataProvider as $item):
                            $total = 0;
                            echo '<tr>';
                            echo '<td >'.$no.'</td>';
                            echo '<td>'.$item['nama'].'</td>';
                            for($i = 1; $i <= 12 ; $i++):
                                echo '<td  class="text-right">'. $formatter->asDecimal( $item['bulan'][$i]['volume'],2) .'</td>';
                                $total += $item['bulan'][$i]['volume'];
                                $totalBulan[$i] += $item['bulan'][$i]['volume'];
                            endfor;
                            echo '<td class="text-right">'.$formatter->asDecimal( $total,2).'</td>';
                            echo '</tr>';
                          $no++;
                        endforeach;
                      ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <?php
                                $totalAll =0 ;
                                for($i = 1; $i <= 12 ; $i++):
                                    echo '<td  class="text-right">'. $formatter->asDecimal( $totalBulan[$i],2) .'</td>';
                                    $totalAll +=$totalBulan[$i];
                                endfor;
                                echo '<td class="text-right">'.$formatter->asDecimal( $totalAll,2).'</td>';
                            ?>
                        </tr>
                    </tfoot>
                  </table>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
            <div class="card-footer">

            </div><!-- card-footer -->
        </div><!-- card -->
    <div><!-- col -->
</div><!-- row -->
