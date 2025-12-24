<?php

use yii\helpers\Html;

use hscstudio\mimin\components\Mimin;

use backend\components\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use backend\models\ProfilPlant;
use backend\models\Provinsi;
use backend\models\ProvinsiGabungan;
use backend\models\Pulau;
use backend\models\Setting;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DataProyeksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Data Proyeksi';
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
                    <div class="col-2">
                        <?php
                        $tahunAwal = date("Y") + 1;
                        $tahunAkhir = 2023;
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
                /**
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
                 */
                ?>
            </div><!-- card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
                                    'view',
                                    'update',
                                    'delete'
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
                                'urlCreator' => function ($action, $model, $key, $index) {
                                        if ($action === 'view') {
                                            return Url::to(["view", 'tahun' => $model->tahun, 'id_plant' => $model->id_plant]);
                                        }

                                        if ($action === 'update') {
                                            return Url::to(["update", 'tahun' => $model->tahun, 'id_plant' => $model->id_plant]);

                                        }
                                        if ($action === 'delete') {
                                            return Url::to(["delete", 'tahun' => $model->tahun, 'id_plant' => $model->id_plant]);

                                        }
                                    },
                                'visibleButtons' => [
                                    'update' => function ($model) {
                                            $setting = Setting::find()->one();
                                            if ($setting->lock_data_proyeksi == 0) {
                                                $datetime1 = new DateTime('NOW');
                                                $datetime2 = new DateTime($model->dateAdd);
                                                $difference = $datetime1->diff($datetime2);
                                                if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
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
                                            if ($setting->lock_data_proyeksi == 0) {
                                                $datetime1 = new DateTime('NOW');
                                                $datetime2 = new DateTime($model->dateAdd);
                                                $difference = $datetime1->diff($datetime2);
                                                if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
                                                    if ($difference->m < 3 && $difference->y == 0) {
                                                        return true;
                                                    } else {
                                                        return false;
                                                    }
                                                } else if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
                                                    return true;
                                                }
                                            }else{
                                                return true;
                                            }
                                        },
                                ]
                            ],
                            'tahun',
                            [
                                'attribute' => 'provinsi_plant',
                                'value' => 'plant.plant.provinsi.nama',
                                'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Provinsi::find()->asArray()->all(), 'id', 'nama') : '',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id),
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
                                'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(ProfilPlant::find()->asArray()->all(), 'id', 'nama_plant') : '',
                                'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) || \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id),
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