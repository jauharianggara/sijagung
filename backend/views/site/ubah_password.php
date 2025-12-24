<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\UnitUsaha */

$this->title = 'Ubah Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

			<div class="card">
				<div class="card-header with-border">
					<h3 class="card-title">Ubah <strong>Password</strong></h3>
					<div class="card-tools pull-right"></div>
				</div>

  			<div class="card-body">
          <?= $form->field($model, 'old_password')->passwordInput()  ?>
          <?= $form->field($model, 'new_password')->passwordInput()  ?>
      		<?= $form->field($model, 'repeat_password')->passwordInput()  ?>

        </div>

				<div class="card-footer">
						<?=  Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
						<div class="float-right">
								<?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
						</div><!-- float-right -->
				</div><!-- card-footer -->
			</div>
<?php ActiveForm::end(); ?>
