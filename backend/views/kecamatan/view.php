<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\components\Mimin;
/* @var $this yii\web\View */
/* @var $model backend\models\Kecamatan */

$this->title = "View Kecamatan: $model->nama";
$this->params['breadcrumbs'][] = ['label' => 'Kecamatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nama;
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">
                
            </div>

            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'id_kota_kabupaten',
                        'id_provinsi:integer',
                        'nama',
                        'latitude',
                        'longitude',
                        'userAdd:integer',
                        'dateAdd',
                        'userEdit:integer',
                        'dateEdit',
                    ],
                ]) ?>
            </div>

            <div class="card-footer">
                <?=  Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                <div class="float-right">
                    <?php 
                            if ((Mimin::checkRoute($this->context->id.'/update'))){
                                echo Html::a(
                                    '<i class=\'fa fa-edit\'></i> Ubah', 
                                    ['create'], 
                                    ['class'=>'btn waves-effect waves-light btn-primary'
                                ]).' ';
                            }
                        ?>
                        <?php 
                            if ((Mimin::checkRoute($this->context->id.'/delete'))){
                                echo Html::a(
                                    '<i class=\'fa fa-trash\'></i> Hapus', 
                                    ['export'], 
                                    [
                                        'class'=>'btn waves-effect waves-light btn-danger',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ],
                                    ).' ';
                            }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>