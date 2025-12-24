<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profil-plant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="card-header accordion" data-toggle="collapse" data-target="#search-box" aria-expanded="true">
        <h4 class="card-title">
            Filter Berdasarkan
            <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
        </h4>
    </div><!-- card-header -->
    <div class="card-body collapse show" id="search-box">
        <div class="row">
        <div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?= $form->field($model, 'id') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?= $form->field($model, 'kode_plant') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?= $form->field($model, 'id_user') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?= $form->field($model, 'nama_plant') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?= $form->field($model, 'nama_lengkap') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'telpon') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'email') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'silo') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'kapasitas_silo') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'dryer') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'kapasitas') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'dateAdd') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'userAdd') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'dateEdit') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'userEdit') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'longitude') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'latitude') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'id_perusahaan') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'kapasitas_terpasang') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_nama_perusahaan') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_jalan') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_desa') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_kecamatan') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_provinsi') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_kota') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_koordinat') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_telepon') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_fax') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_email') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_longitude') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'ip_latitude') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'pic_nama') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'pic_handphone') ?>

</div>

<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>

    <?php // echo $form->field($model, 'pic_email') ?>

</div>

        </div>

        <div class="float-right">
            <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['btn waves-effect waves-light btn-primary']) ?>
            <?= Html::resetButton('<i class="fa fa-reload"></i> Reset', ['btn waves-effect waves-light btn-secondary']) ?>
        </div><!-- float-right -->
    </div><!-- row -->
    <?php ActiveForm::end(); ?>
</div><!-- card-body -->
