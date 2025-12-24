<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Carousel;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use backend\assets\AppLandingAsset;
use common\widgets\Alert;
use yii\helpers\Url;


AppLandingAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

</head>
<body>
    <div id="background"></div>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'SIJAGUNG 2.0',
        'brandUrl' => Url::toRoute('site/landing'),
        'options' => [
            'class' => 'navbar navbar-neraca navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/landing']],
        ['label' => 'Data Pembelian', 'url' => ['/site/data-pembelian']],
        ['label' => 'Data Stok', 'url' => ['/site/data-stok']],
        ['label' => 'Data Proyeksi', 'url' => ['/site/data-proyeksi']],
        ['label' => 'Login', 'url' => ['/site/login']],
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
