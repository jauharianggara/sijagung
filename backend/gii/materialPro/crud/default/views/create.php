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

$this->title = <?= $generator->generateString('Tambah ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= "<?php " ?>$form = ActiveForm::begin(); ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
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
  	                <?= "<?= " ?> Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    <div class="float-right">
                        <?= "<?= " ?> Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' =>'btn waves-effect waves-light btn-primary']) ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
<?= "<?php " ?>ActiveForm::end(); ?>
