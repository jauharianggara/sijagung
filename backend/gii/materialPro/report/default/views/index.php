<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(StringHelper::basename($generator->reportName)) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(StringHelper::basename($generator->reportName)) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= "<?php " ?>$form = ActiveForm::begin(['method'=>'get']); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">

            </div><!-- card-header -->

            <div class="card-body">
                <?= "<?= " ?>$this->render('_form', [
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div><!-- card-body -->

            <div class="card-footer">
                <?= "<?= " ?> Html::resetButton('<i class="fa fa-sync"></i> Reset', ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                <div class="float-right">
                    <?= "<?= " ?> Html::submitButton('<i class="fa fa-search"></i> Tampil', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                </div><!-- float-right -->
            </div><!-- card-footer -->
        </div><!-- card -->
    </div><!-- col -->
</div><!-- row -->
<?= "<?php " ?>ActiveForm::end(); ?>
