<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\InputDataPembelian */

$this->title = 'Info Update Data Profile Plant';
//$this->params['breadcrumbs'][] = ['label' => 'Info Update Data Profile Plant', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-data-pembelian-view">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-warning">
				<div class="box-header with-border ">
					<h3 class="box-title"><i class="icon fa fa-warning"></i> MOHON PERHATIAN!</h3>

					<div class="box-tools pull-right">

					</div>
		  </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <span class="">
              Mohon untuk SEMUA Plant : WAJIB terlebih dulu melakukan update data SILO, KAPASITAS TERPASANG dan Dryer sebelum melanjutkan mengisi data bulanan. Terima Kasih.
            </span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

          </div>
        </div>

      </div>

      <div class="box-footer">
        <?php
          echo Html::a('<i class="fa fa-pencil"></i> Update Data', ['/profil-plant/update','id'=>\Yii::$app->user->identity->plant->id], ['class'=>'btn btn-primary']) ;
        ?>
      </div>
    </div>
  </div>
</div>
