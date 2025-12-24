<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\ProfilPlant */
/* @var $form yii\widgets\ActiveForm */
?>

<?= $form->field($model, 'nama_plant')->textInput(['maxlength' => true])->label('Nama Perusahaan'); ?>

<?php
$dataProvinsi = ArrayHelper::map(\backend\models\Provinsi::find()->select(['id', 'nama'])->orderBy('nama')->asArray()->all(), 'id', 'nama');
echo $form->field($model, 'ip_provinsi')->dropDownList(
    $dataProvinsi,
    [
        'id' => 'ip_provinsi',
        'prompt' => '-- Pilih Provinsi --',
        'onchange' => '
          				$.post( "' . Yii::$app->urlManager->createUrl('kota-kabupaten/list?id=') . '"+$(this).val(), function( data ) {
          				  $( "select#ip_kota" ).html( data );
          				});
          			'
    ]
)->label('Provinsi');
?>
<?php
if (!$model->isNewRecord) {
    $dataKotaKabupaten = ArrayHelper::map(\backend\models\KotaKabupaten::find()->select(['id', 'nama'])->where(['id_provinsi' => $model->ip_provinsi])->orderBy('nama')->asArray()->all(), 'id', 'nama');
}
echo $form->field($model, 'ip_kota')->dropDownList(
    $dataKotaKabupaten,
    [
        'id' => 'ip_kota',
        'prompt' => '-- Pilih Kota / Kabupaten --',
        'onchange' => '
          				$.post( "' . Yii::$app->urlManager->createUrl('kecamatan/list?id=') . '"+$(this).val(), function( data ) {
          				  $( "select#ip_kecamatan" ).html( data );
          				});
          			'
    ]
)->label('Kota / Kabupaten');
?>
<?php
if (!$model->isNewRecord) {
    $dataKecamatan = ArrayHelper::map(\backend\models\Kecamatan::find()->select(['id', 'nama'])->where(['id_kota_kabupaten' => $model->ip_kota])->orderBy('nama')->asArray()->all(), 'id', 'nama');
}
echo $form->field($model, 'ip_kecamatan')->dropDownList(
    $dataKecamatan,
    [
        'id' => 'ip_kecamatan',
        'prompt' => '-- Pilih Kecamatan --',
        'onchange' => '
          				$.post( "' . Yii::$app->urlManager->createUrl('kelurahan/list?id=') . '"+$(this).val(), function( data ) {
          				  $( "select#ip_desa" ).html( data );
          				});
          			'
    ]
)->label('Kecamatan');
?>
<?php
if (!$model->isNewRecord) {
    $dataKelurahan = ArrayHelper::map(\backend\models\Kelurahan::find()->select(['id', 'nama'])->where(['id_kecamatan' => $model->ip_kecamatan])->orderBy('nama')->asArray()->all(), 'id', 'nama');
}
echo $form->field($model, 'ip_desa')->dropDownList(
    $dataKelurahan,
    [
        'id' => 'ip_desa',
        'prompt' => '-- Pilih Kelurahan --',
    ]
)->label('Kelurahan');
?>

<?= $form->field($model, 'ip_jalan')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'longitude')->textInput() ?>

<?= $form->field($model, 'latitude')->textInput() ?>

<?= $form->field($model, 'ip_telepon')->textInput() ?>

<?= $form->field($model, 'ip_fax')->textInput() ?>

<?= $form->field($model, 'ip_email')->textInput(['maxlength' => true]) ?>

<?php
if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {

    echo $form->field($model, 'status')->dropDownList(
        [
            0 => 'Non Aktif',
            1 => 'Aktif',
        ],
        [
            'id' => 'status',
            'prompt' => '-- Pilih Status --',
        ]
    );
}
?>