<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */
$this->title = "Ubah Profil Plant: $model->nama_plant";
$this->params['breadcrumbs'][] = ['label' => 'Profil Plant', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_plant, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <!-- Custom Tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <!-- item profil-plant-->
                    <li class="nav-item"><a class="nav-link active show" href="#tab-identitas-perusahaan"
                            data-toggle="tab" aria-expanded="false" role="tab">Identitas Perusahaan</a></li>
                    <!-- item identitas-perusahaan-->
                    <li class="nav-item "><a class="nav-link" href="#tab-profil-plant" data-toggle="tab"
                            aria-expanded="true" role="tab">Profil Plant</a></li>
                    <!-- item profil-pic-->
                    <li class="nav-item"><a class="nav-link" href="#tab-profil-pic" data-toggle="tab"
                            aria-expanded="false" role="tab">Profil PIC</a></li>
                    <!-- item profil-pic-->
                    <li class="nav-item"><a class="nav-link" href="#tab-history" data-toggle="tab" aria-expanded="false"
                            role="tab">Perubahan Infrstruktur</a></li>
                </ul>

                <div class="tab-content tabcontent-border">
                    <!-- pane profil-plant-->
                    <!-- pane profil-plant-->
                    <div class="tab-pane p-20 active" id="tab-identitas-perusahaan" role="tabpanel">
                        <?= $this->render('_form_identitas', [
                            'model' => $model,
                            'form' => $form,
                        ]) ?>
                    </div><!-- /.tab-pane -->

                    <div class="tab-pane p-20" id="tab-profil-plant" role="tabpanel">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'form' => $form,
                        ]) ?>
                    </div><!-- /.tab-pane -->



                    <!-- pane profil-plant-->
                    <div class="tab-pane p-20" id="tab-profil-pic" role="tabpanel">
                        <?= $this->render('_form_profil', [
                            'model' => $model,
                            'form' => $form,
                        ]) ?>
                    </div><!-- /.tab-pane -->
                    <div class="tab-pane p-20" id="tab-history" role="tabpanel">
                        <?= $this->render('_grid_history', [
                            'model' => $model,
                            'form' => $form,
                        ]) ?>
                    </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
            </div><!-- nav-tabs-custom -->
        </div>

        <div class="card-footer">
            <?= Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>

            <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
</div>
<?php ActiveForm::end(); ?>