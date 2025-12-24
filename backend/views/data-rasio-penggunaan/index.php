<?php

use yii\helpers\Html;
use yii\grid\GridView;
use hscstudio\mimin\components\Mimin;
use yii\helpers\ArrayHelper;
use backend\models\ProfilPlant;
use backend\models\Provinsi;
use backend\models\InputDataPembelian;
use backend\models\ProvinsiGabungan;
use backend\models\Pulau;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InputDataPembelianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Stock To Use Ratio';
$this->params['breadcrumbs'][] = $this->title;

$tahunAwal = date("Y");
$tahunAkhir = 2019;
?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
]); ?>

<div class="row">
    <div class="col-12">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>
        <div class="card">
            <div class="card-header accordion" data-toggle="collapse" data-target="#search-box" aria-expanded="true">
                <h4 class="card-title">
                    Filter Berdasarkan
                    <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
                </h4>
            </div>
            <div class="card-body collapse show" id="search-box">
                <div class="row">
                    <div class="col-12">
                    <?php
                        if(\Yii::$app->authManager-> getAssignment('admin',\Yii::$app->user->id)){
                            $dataPlant = ArrayHelper::map(ProfilPlant::find()->orderBy(['nama_plant'=>'ASC'])->asArray()->all(), 'id_user', 'nama_plant');
                            echo $form->field($searchModel, 'id_user')->dropDownList(
                            $dataPlant,
                            [
                            'id'=>'id_user',
                            'prompt'=>'-- Plant --',
                            ]);
                        }
                        ?>
                    </div>
                </div>

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
                <div class="text-right">
                    <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?php
                if ((Mimin::checkRoute($this->context->id . '/data-proyeksi/export'))) {
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
                                'attribute' => 'tahun',
                            ],
                            [
                                'attribute' => 'bulan',
                                'value' => 'bulan_label',
                            ],
                            [
                                'attribute' => 'provinsi_plant',
                                //'value' => 'plant.plant.provinsi.nama',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                            ],
                            [
                                'attribute' => 'provinsi_gabungan_plant',
                                //'value' => 'plant.plant.provinsi.provinsiGabungan.nama',
                                'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(ProvinsiGabungan::find()->asArray()->all(), 'id', 'nama') : '',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                            ],
                            [
                                'attribute' => 'pulau_plant',
                                //'value' => 'plant.plant.provinsi.pulau.nama',
                                'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Pulau::find()->asArray()->all(), 'id', 'nama') : '',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                            ],
                            [
                                'attribute' => 'nama_plant',
                                //'value' => 'plant.plant.nama_plant',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                            ],
                            [
                                'attribute' => 'stok',
                                'header'=>'Jumlah Stok (Ton)',
                                'filter' => false,
                                'format' =>'integer',
                                'contentOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'attribute' => 'penggunaan',
                                'header'=>'Penggunaan (Ton)',
                                'filter' => false,
                                'format' =>'integer',
                                'contentOptions' => ['class' => 'text-right'],
                            ],
                            [
                                'attribute' => 'rasioPenggunaan',
                                'header'=>'Stock to Use Ratio(%)',
                                'filter' => false,
                                'format' => ['decimal', 2],
                                'contentOptions' => ['class' => 'text-right'],
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