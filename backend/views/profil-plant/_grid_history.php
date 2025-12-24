<?php
use backend\components\GridView;
use yii\data\ActiveDataProvider;


$dataProvider = new ActiveDataProvider([
    'query' => $model->getProfilPlantHistory(),
    'sort' => [
        'defaultOrder' => [
            'tanggal' => SORT_DESC,
        ]
    ],
]);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
                            'tanggal:datetime',
                            [
                                'attribute'=> 'kapasitas_silo_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_dryer_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            [
                                'attribute'=> 'kapasitas_produksi_sekarang',
                                'format'=> 'integer',
                                'contentOptions'=>[
                                    'class'=>'text-right'
                                ]
                            ],
                            'jenis_produksi_pakan',
                        ],
                    ]); ?>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
            <div class="card-footer">

            </div><!-- card-footer -->
        </div><!-- card -->
    <div><!-- col -->
</div><!-- row -->