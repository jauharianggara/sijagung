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
$this->title = 'Laporan Proyeksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?php 
                    if ((Mimin::checkRoute($this->context->id . '/data-proyeksi/export'))) {
                        $urlGet =  http_build_query(Yii::$app->request->queryParams);
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
                							'attribute'=>'tahun',
                						],
                						[
                							'attribute'=>'bulan',
                							'value' => 'bulanLabel',
                						],
                                        [
                                            'attribute' => 'provinsi_plant',
                							'value' => 'plant.plant.provinsi.nama',
                                            'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                						],
                                        [
                                            'attribute' => 'provinsi_gabungan_plant',
                                            'value' => 'plant.plant.provinsi.provinsiGabungan.nama',
                                            'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(ProvinsiGabungan::find()->asArray()->all(), 'id', 'nama') : '',
                                            'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                        ],
                                        [
                                            'attribute' => 'pulau_plant',
                                            'value' => 'plant.plant.provinsi.pulau.nama',
                                            'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Pulau::find()->asArray()->all(), 'id', 'nama') : '',
                                            'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                        ],
                                        [
                							'attribute' => 'nama_plant',
                							'value' => 'plant.plant.nama_plant',
                                            'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                						],
                                        
                                        [
                							'attribute'=>'volume',
                							'filter'=>false,
                							'format'=>['decimal',2],
                							'contentOptions' => ['class' => 'text-right'],
                						],
                						[
                							'attribute'=>'kadar_air_persen',
                							'filter'=>false,
                							'format'=>['decimal',2],
                							'contentOptions' => ['class' => 'text-right'],
                						],
                						[
                							'attribute'=>'konversiVolume14',
                							'header'=>'Volume 14%(Kg)',
                							'filter'=>false,
                							'format'=>['decimal',2],
                							'contentOptions' => ['class' => 'text-right'],
                							'visible' => \Yii::$app->authManager->getAssignment('admin',\Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminProvinsi',\Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('adminKotaKabupaten',\Yii::$app->user->id),
                						],
                						[
                							'attribute'=>'dateEdit',
                							'header'=>'Last Edited',
                							'filter'=>false,
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
