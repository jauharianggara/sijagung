<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProvinsiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provinsi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'latitude') ?>

    <?= $form->field($model, 'longitude') ?>

    <?= $form->field($model, 'userAdd') ?>

    <?php // echo $form->field($model, 'dateAdd') ?>

    <?php // echo $form->field($model, 'userEdit') ?>

    <?php // echo $form->field($model, 'dateEdit') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
