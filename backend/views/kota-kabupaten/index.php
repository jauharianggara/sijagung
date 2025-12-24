<?php

use yii\helpers\Html;
use hscstudio\mimin\components\Mimin;

use backend\components\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Provinsi;
use backend\models\KotaKabupaten;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\KotaKabupatenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kota Kabupaten';
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
                        <div class="col-md-6">
                            <?php
                            $dataProvinsi = ArrayHelper::map(Provinsi::find()->orderBy(['nama' => SORT_ASC])->asArray()->all(), 'id', 'nama');
                            echo $form->field($searchModel, 'id_provinsi')->widget(Select2::classname(), [
                                'data' => $dataProvinsi,
                                'options' => [
                                    'placeholder' => 'Pilih Provinsi ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,

                                ],
                            ])->label(false);
                            ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($searchModel, 'nama')->textInput(['class' => 'form-control', 'placeholder' => 'Nama'])->label(false) ?>
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
                                'attribute' => 'id_provinsi',
                                'value' => function ($model) {
                                    return $model->provinsi->nama;
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