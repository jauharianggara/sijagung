<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <section id="wrapper" class="login-register login-sidebar" style="background-image:url(../images/background/login-register.jpg);">
            <?php $this->beginBody() ?>

            <?= $content ?>

            <?php $this->endBody() ?>
        </section>
    </body>

</html>
<?php $this->endPage() ?>
