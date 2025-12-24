<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
<?php if ($generator->enablePjax): ?>
        'options' => [
            'data-pjax' => 1
        ],
<?php endif; ?>
    ]); ?>

    <div class="card-header accordion" data-toggle="collapse" data-target="#search-box" aria-expanded="true">
        <h4 class="card-title">
            Filter Berdasarkan
            <span class="accicon"><i class="fas fa-angle-down rotate-icon"></i></span>
        </h4>
    </div><!-- card-header -->
    <div class="card-body collapse show" id="search-box">
        <div class="row">
        <?php
            $count = 0;
            foreach ($generator->getColumnNames() as $attribute) {
                if (++$count < 6) {
                    echo "<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>\n\n";
                    echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                    echo "</div>\n\n";
                } else {
                    echo "<div class='col-sm-12 col-md-12 col-lg-12 col-xl-12'>\n\n";
                    echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                    echo "</div>\n\n";
                }
            }
        ?>
        </div>

        <div class="float-right">
            <?= "<?= " ?>Html::submitButton('<i class="fa fa-search"></i> Filter', ['btn waves-effect waves-light btn-primary']) ?>
            <?= "<?= " ?>Html::resetButton('<i class="fa fa-reload"></i> Reset', ['btn waves-effect waves-light btn-secondary']) ?>
        </div><!-- float-right -->
    </div><!-- row -->
    <?= "<?php " ?>ActiveForm::end(); ?>
</div><!-- card-body -->
