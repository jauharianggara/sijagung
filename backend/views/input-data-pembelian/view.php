<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\components\Mimin;

/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */

$this->title = "View Input Data Pembelian: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Input Data Pembelian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="input-data-pembelian-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'bulan',
                            'tahun',
                            'kapo',
                            'harga_po',
                            'harga_ka',
                            'quantity_terima',
                            'harga_terima',
                            'ka_terima',
                            'asal_jagung',
                            'pemasok',
                            'dateAdd',
                            'dateEdit',
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
</div>