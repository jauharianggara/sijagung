<?php

use yii\helpers\Html;

use hscstudio\mimin\components\Mimin;

use backend\components\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Berita';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berita-index">

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
                            return Html::a (
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

                //'id',
                'judul',
                //'isi',
                'imageUpload',
                'fileUpload',
          			[
          				'attribute'=>'dateAdd',
          				'header'=>'Last Edited',
          				'format' => ['date', 'php:d F Y H:i'],
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
