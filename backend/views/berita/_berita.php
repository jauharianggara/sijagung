<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
  <div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">
            <?= $model->judul?>
          </h3>
					<div class="box-tools pull-right">
            <?= $model->dateAdd?>
					</div>
		  </div>

      <div class="card-body">
        <?= $model->isi ?>
        <img class="img-responsive" src="<?= \yii\helpers\Url::base(true).$model->imageUpload?>" alt="">
        <?php
          if($model->fileUpload != ''){
            echo "<a href='".\yii\helpers\Url::base(true).$model->fileUpload."' class='btn btn-primary'><i class='glyphicon glyphicon-download'></i>Download File</a>";
          }
        ?>

  </div>

  <div class="card-footer">

  </div>
</div>
</div>
</div>
</div>
