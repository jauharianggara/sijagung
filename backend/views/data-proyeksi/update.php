<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DataProyeksi */
$this->title = "Ubah Data Proyeksi: $model->id";
$this->params['breadcrumbs'][] = ['label' => 'Data Proyeksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(['id'=>'frm-input-data-proyeksi']); ?>

<div class="data-proyeksi-update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">
                </div><!-- card-header -->

                <div class="card-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'form' => $form,
                        'data' => $data,
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
