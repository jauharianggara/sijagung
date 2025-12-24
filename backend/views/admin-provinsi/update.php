<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminProvinsi */
$this->title = "Ubah Admin Provinsi: $model->nama";
$this->params['breadcrumbs'][] = ['label' => 'Admin Provinsi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(); ?>

<div class="admin-provinsi-update">
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
                        <?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
<?php ActiveForm::end(); ?>
