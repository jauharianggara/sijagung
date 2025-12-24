<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

use hscstudio\mimin\components\Mimin;
use backend\components\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */

$this->title = "View Profil Plant: $model->nama_plant";
$this->params['breadcrumbs'][] = ['label' => 'Profil Plant', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nama_plant;

$dataProvider = new ActiveDataProvider([
    'query' => $model->getProfilPlantHistory(),
    'sort' => [
        'defaultOrder' => [
            'tanggal' => SORT_DESC,
        ]
    ],
]);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">

            </div><!-- card-header -->

            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'kode_plant',
                        'nama_plant',
                        'nama_lengkap',
                        'telpon',
                        'email:email',
                        'silo',
                        'kapasitas_silo',
                        'dryer',
                        'kapasitas',
                        'dateAdd',
                        'dateEdit',
                        'longitude',
                        'latitude',
                        'kapasitas_terpasang',
                        'ip_nama_perusahaan',
                        'ip_jalan',
                        'ip_desa',
                        'ip_kecamatan',
                        'ip_provinsi',
                        'ip_kota',
                        'ip_koordinat',
                        'ip_telepon',
                        'ip_fax',
                        'ip_email:email',
                        'ip_longitude',
                        'ip_latitude',
                        'pic_nama',
                        'pic_handphone',
                        'pic_email:email',
                        [
                            'attribute' => 'user.nama',
                            'label' => 'User',
                        ],
                    ],
                ]) ?>
            </div><!-- card-body -->

            <div class="card-footer">
                <?= Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                <div class="float-right">
                    <?php if ((Mimin::checkRoute($this->context->id . '/update'))) {
                        echo Html::a(
                            '<i class=\'fa fa-edit\'></i> Ubah',
                            ['update', 'id' => $model->id],
                            [
                                'class' => 'btn waves-effect waves-light btn-primary'
                            ]
                        ) . ' ';
                    }

                    if ((Mimin::checkRoute($this->context->id . '/delete'))) {
                        echo Html::a(
                            '<i class=\'fa fa-trash\'></i> Hapus',
                            ['delete', 'id' => $model->id],
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">

            </div><!-- card-header -->

            <div class="card-body">
            <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => 'No.',
                            ],
                            'tanggal:datetime',
                            [
                                'attribute'=> 'kapasitas_silo_sebelumnya',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_silo_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_dryer_sebelumnya',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_dryer_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_produksi_sebelumnya',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_produksi_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                        ],
                    ]); ?>
            </div><!-- card-body -->

            <div class="card-footer">

            </div><!-- card-footer -->
        </div><!-- card -->
    </div><!-- col -->
</div><!-- row -->