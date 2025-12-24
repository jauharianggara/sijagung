<?php

use yii\helpers\Html;
use hscstudio\mimin\components\Mimin;

use backend\components\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Provinsi;
use backend\models\KotaKabupaten;
use backend\models\Kecamatan;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\KecamatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kecamatan';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                    <?php
                    if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                    ?>
                        <div class="col-md-4">
                            <?php
                            $dataProvinsi = ArrayHelper::map(Provinsi::find()->orderBy(['nama' => SORT_ASC])->asArray()->all(), 'id', 'nama');
                            echo $form->field($searchModel, 'id_provinsi')->widget(Select2::classname(), [
                                'data' => $dataProvinsi,
                                'options' => [
                                    'id' => 'id_provinsi',
                                    'placeholder' => 'Pilih Provinsi ...',
                                    'onchange' => '
                                        $.post( "' . Yii::$app->urlManager->createUrl('kota-kabupaten/list?id=') . '"+$(this).val(), function( data ) {
                                        $( "select#id_kota_kabupaten" ).html( data );
                                        });
                                    ',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,

                                ],
                            ])->label(false);
                            ?>
                        </div>

                        <div class="col-4">
                            <?php
                            $dataKotaKabupaten = ArrayHelper::map(KotaKabupaten::find()->where(['id_provinsi' => $searchModel->id_provinsi])->orderBy(['nama' => SORT_ASC])->asArray()->all(), 'id', 'nama');
                            echo $form->field($searchModel, 'id_kota_kabupaten')->widget(Select2::classname(), [
                                'data' => $dataKotaKabupaten,
                                'options' => [
                                    'id' => 'id_kota_kabupaten',
                                    'placeholder' => 'Pilih Kota Kabupaten ...',
                                    'onchange' => '
                                        $.post( "' . Yii::$app->urlManager->createUrl('kecamatan/list?id=') . '"+$(this).val(), function( data ) {
                                        $( "select#id_kecamatan" ).html( data );
                                    });
                                ',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,
                                ],
                            ])->label(false);
                            ?>
                        </div>

                        <div class="col-4">
                            <?= $form->field($searchModel, 'nama')->textInput(['class' => 'form-control tanggal', 'placeholder' => 'Nama'])->label(false) ?>
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
                ?>
                <?php
                if ((Mimin::checkRoute($this->context->id . '/export'))) {
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
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => 'No.',
                            ],
                            [
                                'options' => [
                                    'width' => '160px',
                                ],
                                'header' => 'Action',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => Mimin::filterActionColumn([
                                    'view', 'update', 'delete'
                                ], $this->context->route),
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fa fa-eye"></span> View',
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'View'),
                                                'class' => 'btn btn-outline-secondary',
                                                'target' => '_blank',
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
                                                'target' => '_blank',
                                            ]
                                        );
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="fa fa-trash"></span> Hapus',
                                            $url,
                                            [
                                                'title' => Yii::t('app', 'Hapus' ),
                                                'class' => 'btn btn-outline-danger',
                                                'data' => [
                                                    'confirm' => 'Hapus data?.',
                                                    'method' => 'post',
                                                ],
                                            ]
                                        );
                                    },
                                ],
                            ],
                            [
                                'attribute' => 'id_provinsi',
                                'value' => function ($model) {
                                    return $model->provinsi->nama;
                                }
                            ],
                            [
                                'attribute' => 'id_kota_kabupaten',
                                'value' => function ($model) {
                                    return $model->kota->nama;
                                }
                            ],
                            'nama',
                            'latitude',
                            'longitude',


                        ],
                    ]); ?>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>