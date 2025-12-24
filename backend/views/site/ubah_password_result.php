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
          <?= $hasil?>
        </div>

       <div class="card-footer">
        </div>
			</div>
<?php ActiveForm::end(); ?>
