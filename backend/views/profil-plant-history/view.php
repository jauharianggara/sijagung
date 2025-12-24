<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\components\Mimin;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlantHistory */

$this->title = "View Profil Plant History: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Profil Plant History', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="profil-plant-history-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                    <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                            'tanggal',
            'dateAdd',
            'dateEdit',
            'kapasitas_silo_sebelumnya',
            'kapasitas_silo_sekarang',
            'kapasitas_dryer_sebelumnya',
            'kapasitas_dryer_sekarang',
            'kapasitas_produksi_sebelumnya',
            'kapasitas_produksi_sekarang',
			[
                                        				'attribute'=>'profilPlant.nama',
                                        				'label'=>'Profil Plant',
                                        			],
                        ],
                    ]) ?>
                </div><!-- card-body -->

                <div class="card-footer">
                    <?= Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    <div class="float-right">
                        <?php                         if ((Mimin::checkRoute($this->context->id . '/update'))) {
                            echo Html::a(
                                '<i class=\'fa fa-edit\'></i> Ubah',
                                ['update','id'=>$model->id],
                                [
                                    'class' => 'btn waves-effect waves-light btn-primary'
                                ]
                            ) . ' ';
                        }

                        if ((Mimin::checkRoute($this->context->id . '/delete'))) {
                            echo Html::a(
                                '<i class=\'fa fa-trash\'></i> Hapus',
                                ['delete','id'=>$model->id],
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
