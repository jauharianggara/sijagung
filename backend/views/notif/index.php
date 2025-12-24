<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\components\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\KelurahanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notif';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">

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
                                'template' => '{view}',
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
                                ],
                                'urlCreator' => function ($action, $model, $key, $index) {
                                  if ($action === 'view') {
                                      return Url::to([$model->notif->notifType->route, 'id' =>$model->notif->id_object]);
                                  }
                                },
                            ],
                            'notif.tanggal',
                            'notif.judul',
                            'notif.keterangan',
                            [
                              'header'=>'User',
                              'attribute'=>'notif.user.nama',
                            ]
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
