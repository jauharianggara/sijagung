<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\Kasus */

$this->title = 'Tambah Kecamatan';
$this->params['breadcrumbs'][] = ['label' => 'Kecamatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">
            </div>

            <div class="card-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div>

            <div class="card-footer">
  	            <?=  Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                <div class="float-right">
                    <?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
