<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use hscstudio\mimin\components\Mimin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BeritaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Berita';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berita-index">
  <?php
  echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_berita',
  ]);
  ?>
</div>
