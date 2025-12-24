<?php

use yii\helpers\Html;
use yii\grid\GridView;
use hscstudio\mimin\components\Mimin;
use yii\helpers\ArrayHelper;
use backend\models\ProfilPlant;
use backend\models\Provinsi;
use backend\models\ProvinsiGabungan;
use backend\models\Pulau;
use backend\models\Setting;

use backend\models\InputDataPembelian;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InputDataPembelianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Data Pembelian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-data-pembelian-index">

    <div class="row">
        <div class="col-12">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
            ]); ?>
            <div class="card">
                <div class="card-header accordion" data-toggle="collapse" data-target="#search-box"
                    aria-expanded="true">
                    <h4 class="card-title">
                        Filter Berdasarkan
                        <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                    </h4>
                </div>
                <div class="card-body collapse show" id="search-box">
                    <div class="row">
                        <div class="col-2">
                            <?php
                            $dataBulan = [
                                '1' => 'Januari',
                                '2' => 'Februari',
                                '3' => 'Maret',
                                '4' => 'April',
                                '5' => 'Mei',
                                '6' => 'Juni',
                                '7' => 'Juli',
                                '8' => 'Agustus',
                                '9' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ];

                            echo $form->field($searchModel, 'bulan')->widget(Select2::classname(), [
                                'data' => $dataBulan,
                                'options' => [
                                    'placeholder' => '-- Bulan --',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => true,
                                ],
                            ])->label(false); ?>
                        </div>

                        <div class="col-2">
                            <?php
                            $tahunAwal = date("Y");
                            $tahunAkhir = 2019;
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

                        <?php
                        if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                            ?>
                            <div class="col-5">
                                <?php
                                $dataPlant = ArrayHelper::map(ProfilPlant::find()->orderBy(['nama_plant' => 'ASC'])->asArray()->all(), 'id', 'nama_plant');
                                echo $form->field($searchModel, 'nama_plant')->dropDownList(
                                    $dataPlant,
                                    [
                                        'id' => 'plant',
                                        'prompt' => '-- Plant --',
                                    ]
                                )->label(false);
                                ?>
                            </div>
                            <?php
                        }
                        ?>

                        <?php
                        if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                            ?>
                            <div class="col-3">
                                <?php
                                $dataProvinsi = ArrayHelper::map(Provinsi::find()->orderBy(['nama' => 'ASC'])->asArray()->all(), 'id', 'nama');
                                echo $form->field($searchModel, 'provinsi_plant')->dropDownList(
                                    $dataProvinsi,
                                    [
                                        'id' => 'provinsi',
                                        'prompt' => '-- Provinsi --',
                                    ]
                                )->label(false);
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="text-right">
                        <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <div class="card">
                <div class="card-header">
                    <?php
                    if ((Mimin::checkRoute($this->context->id . '/create'))) {
                        echo Html::a(
                            '<i class=\'fa fa-plus\'></i> Tambah',
                            ['create'],
                            [
                                'class' => 'btn waves-effect waves-light btn-primary'
                            ]
                        ) . ' ';
                    }
                    if ((Mimin::checkRoute($this->context->id . '/export'))) {
                        $urlGet = http_build_query(Yii::$app->request->queryParams);
                        echo Html::a(
                            '<i class=\'fa fa-download\'></i> Export',
                            ['export?' . $urlGet],
                            [
                                'class' => 'btn waves-effect waves-light btn-secondary'
                            ]
                        ) . ' ';
                    }
                    ?>
                </div><!-- card-header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => Mimin::filterActionColumn([
                                        'update',
                                        'delete',
                                        'view'
                                    ], $this->context->route),
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="fa fa-eye"></span> View',
                                                    $url,
                                                    [
                                                        'title' => Yii::t('app', 'View'),
                                                        'class' => 'btn btn-outline-secondary',
                                                        'target' => '_self',
                                                    ]
                                                );
                                            },
                                        'update' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="fa fa-edit"></span> Ubah',
                                                    $url,
                                                    [
                                                        'title' => Yii::t('app', 'Ubah'),
                                                        'class' => 'btn btn-outline-primary',
                                                        'target' => '_self',
                                                    ]
                                                );
                                            },
                                        'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="fa fa-trash"></span> Hapus',
                                                    $url,
                                                    [
                                                        'title' => Yii::t('app', 'Hapus'),
                                                        'class' => 'btn btn-outline-danger',
                                                        'data' => [
                                                            'confirm' => 'Hapus data?.',
                                                            'method' => 'post',
                                                        ],
                                                    ]
                                                );
                                            },
                                    ],
                                    'visibleButtons' => [
                                        'update' => function ($model) {
                                                //return true;
                                                /**
                                                                           if(\Yii::$app->user->id == 15 || \Yii::$app->user->id == 16 || \Yii::$app->user->id == 17 || \Yii::$app->user->id == 18 || \Yii::$app->user->id == 19 || \Yii::$app->user->id == 20 || \Yii::$app->user->id == 21 || \Yii::$app->user->id == 22){
                                                                               if($model->bulan == 7 AND $model->tahun == 2021){
                                                                                   return true;
                                                                               }

                                                                               if($model->bulan == 6 && $model->tahun == 2021){
                                                                                   return true;
                                                                               }
                                                                           }

                                                                           if(\Yii::$app->user->id == 17){
                                                                               if($model->bulan == 10 AND $model->tahun == 2021){
                                                                                   return true;
                                                                               }
                                                                           }
                                                                           **/


                                                $setting = Setting::find()->one();
                                                if ($setting->lock_data_pembelian == 0) {
                                                    $datetime1 = new DateTime('NOW');
                                                    $datetime2 = new DateTime($model->dateAdd);
                                                    $difference = $datetime1->diff($datetime2);
                                                    if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
                                                        //if ($difference->m < 3 && $difference->y == 0) {
                                                        if ($difference->m < 3 && $difference->y == 0) {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    } else if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                                                        return true;
                                                    }
                                                } else {
                                                    return true;
                                                }

                                            },
                                        'delete' => function ($model) {
                                                $setting = Setting::find()->one();
                                                if ($setting->lock_data_pembelian == 0) {
                                                    $datetime1 = new DateTime('NOW');
                                                    $datetime2 = new DateTime($model->dateAdd);
                                                    $difference = $datetime1->diff($datetime2);
                                                    if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
                                                        //if ($difference->m < 3 && $difference->y == 0) {
                                                        if ($difference->m < 3 && $difference->y == 0) {

                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    } else if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                                                        return true;
                                                    }
                                                } else {
                                                    return true;
                                                }


                                            },
                                    ]
                                ],
                                [
                                    'attribute' => 'tahun',
                                    'filter' => $tahun,
                                ],
                                [
                                    'attribute' => 'bulan',
                                    'value' => 'bulanLabel',
                                    'filter' => array(
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
                                    ),
                                ],
                                [
                                    'attribute' => 'provinsi_plant',
                                    'value' => 'plant.provinsi.nama',
                                    'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Provinsi::find()->asArray()->all(), 'id', 'nama') : '',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'provinsi_gabungan_plant',
                                    'value' => 'plant.provinsi.provinsiGabungan.nama',
                                    'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(ProvinsiGabungan::find()->asArray()->all(), 'id', 'nama') : '',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'pulau_plant',
                                    'value' => 'plant.provinsi.pulau.nama',
                                    'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Pulau::find()->asArray()->all(), 'id', 'nama') : '',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'nama_plant',
                                    'value' => 'plant.nama_plant',
                                    'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(ProfilPlant::find()->asArray()->all(), 'id', 'nama_plant') : '',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'ka_terima',
                                    'filter' => false,
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'quantity_terima',
                                    'filter' => false,
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'harga_terima',
                                    'filter' => false,
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'konversiVolume',
                                    'header' => 'Volume KA 15% (Kg)',
                                    'filter' => false,
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'KonversiHarga',
                                    'header' => 'Harga 15% (Rp/Kg)',
                                    'filter' => false,
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id),

                                ],
                                [
                                    'attribute' => 'konversiVolume14',
                                    'header' => 'Volume KA 14% (Kg)',
                                    'filter' => false,
                                    'format' => ['decimal', 2],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'KonversiHarga14',
                                    'header' => 'Harga 14% (Rp/Kg)',
                                    'filter' => false,
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id),

                                ],

                                //KonversiHarga
                                /**
                                                  [
                                                      'attribute'=>'ka_terima',
                                                      'header'=>'KA Terima',
                                                      'filter'=>false,
                                                      'format'=>['decimal',2],
                                                      'contentOptions' => ['class' => 'text-right'],
                                                  ],
                                                  **/
                                [
                                    'label' => 'Asal Jagung',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                            $result = '<ul>';
                                            foreach ($model->inputDataPembelianSumber as $item) {
                                                $result .= "<li>" . $item->provinsi->nama . "</li>";
                                            }
                                            $result .= '</ul>';
                                            return $result;
                                        }
                                ],
                                [
                                    'label' => 'Pemasok',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                            $result = '<ul>';
                                            foreach ($model->inputDataPembelianSuppliers as $item) {
                                                $result .= "<li>" . $item->supplier->nama . "</li>";
                                            }
                                            $result .= '</ul>';
                                            return $result;
                                        }
                                ],
                                /**
                                                  [
                                                      'attribute'=>'pemasok',
                                                      'header'=>'Pemasok',
                                                      'filter'=>false,
                                                  ],
                                                  **/
                                [
                                    'attribute' => 'dateEdit',
                                    'header' => 'Last Edited',
                                    'filter' => false,
                                    'format' => ['date', 'php:d F Y H:i:s'],
                                ],
                            ],
                        ]); ?>
                    </div><!-- table-responsive -->
                </div><!-- card-body -->
                <div class="card-footer">

                </div><!-- card-footer -->
            </div><!-- card -->
            <div><!-- col -->
            </div><!-- row -->