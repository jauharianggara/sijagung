<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KotaKabupaten */
$this->title = "Update Kota Kabupaten: $model->nama";
$this->params['breadcrumbs'][] = ['label' => 'Kota Kabupaten', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(); ?>

<div class="kota-kabupaten-update">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"></h3>
          <div class="box-tools pull-right">
          </div>
      </div>

        <div class="box-body">
      <?= $this->render('_form', [
          'model' => $model,
          'form' => $form,
      ]) ?>

        <div class="box-footer">
  	      <?=  Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
           <div class="pull-right">
             <?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn btn-primary']) ?>
           </div>
         </div>
       </div>
     </div>
   </div>
  </div>
</div>
<?php ActiveForm::end(); ?>
