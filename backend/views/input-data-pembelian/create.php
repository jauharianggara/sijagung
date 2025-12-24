<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */

$this->title = 'Tambah Input Data Pembelian';
$this->params['breadcrumbs'][] = ['label' => 'Input Data Pembelian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['id'=>'frm-input-data-pembelian']); ?>

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
  	                <?=  Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    <div class="float-right">
                        <?=
                        Html::submitButton(
                          '<i class="fa fa-save"></i> Simpan',
                          [
                            'class' =>'btn waves-effect waves-light btn-primary',
                            'id'=>'btn-save',
                            'data' => [
                                'confirm' => 'Pastikan data yang ada masukkan sudah benar, apa anda yakin untuk menyimpan data?.',
                            ],
                          ]) ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
<?php ActiveForm::end(); ?>
