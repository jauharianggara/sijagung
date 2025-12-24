<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */

$this->title = 'Setting';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['method'=>'post']); ?>
<div class="input-data-pembelian-create">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                <?= $form->field($model, 'lock_data_pembelian')->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => 'Open',
                            'offText' => 'Lock',
                        ]
                    ]) ?>

                <?= $form->field($model, 'lock_data_stok')->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => 'Open',
                            'offText' => 'Lock',
                        ]
                    ]) ?>
                </div><!-- card-body -->

                <div class="card-footer">
                    <div class="float-right">
                        <?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
<?php ActiveForm::end(); ?>
