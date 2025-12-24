<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */

$this->title = 'Laporan Stok';
$this->params['breadcrumbs'][] = ['label' => 'Laporan Stok', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['method'=>'get']); ?>

<div class="input-data-pembelian-create">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'form' => $form,
                    ]) ?>
                </div><!-- card-body -->

                <div class="card-footer">
                    <div class="float-right">
                        <?=  Html::submitButton('<i class="fa fa-eye"></i> Tampil', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
<?php ActiveForm::end(); ?>
