<?php

use yii\helpers\Html;
use yii\grid\GridView;
use hscstudio\mimin\components\Mimin;
use backend\models\Provinsi;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfilPlantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Profil Plant';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profil-plant-index">

    <div class="row">
        <div class="col-12">
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
                            'filterModel' => $searchModel,
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
                                ],
                                [
                                    'attribute' => 'kode_plant',
                                    'value' => function ($model) {
                                            return sprintf("%06s", $model->kode_plant);
                                        }
                                ],
                                'nama_plant',
                                [
                                    'attribute' => 'user.username',
                                    'header' => 'User Name',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'header' => 'Provinsi',
                                    'value' => 'provinsi.nama',
                                    'filter' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id) ? ArrayHelper::map(Provinsi::find()->asArray()->all(), 'id', 'nama') : '',
                                ],
                                [
                                    'attribute' => 'nama_lengkap',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'telpon',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                [
                                    'attribute' => 'email',
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
                                ],
                                'silo',
                                [
                                    'attribute' => 'kapasitas_silo',
                                    'header' => 'Kapasitas Silo',
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                'dryer',
                                [
                                    'attribute' => 'kapasitas',
                                    'header' => 'Kapasitas Dryer',
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],
                                [
                                    'attribute' => 'kapasitas_terpasang',
                                    'header' => 'Kapasitas Terpasang',
                                    'format' => ['decimal', 0],
                                    'contentOptions' => ['class' => 'text-right'],
                                ],

                                [
                                    'label' => 'Jenis Produksi',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                            $result = '<ul>';
                                            foreach ($model->profilPlantJenisProduksi as $item) {
                                                $result .= "<li>" . $item->jenisProduksi->nama . "</li>";
                                            }
                                            $result .= '</ul>';
                                            return $result;
                                        }
                                ],
                                [
                                    'attribute' => 'dateEdit',
                                    'format' => ['date', 'php:d F Y H:i:s'],
                                    'visible' => \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id),
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