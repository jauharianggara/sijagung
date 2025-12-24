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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header with-border">

            </div><!-- card-header -->

            <div class="card-body">
                <div class="table-responsive m-t-20">
                    <table class='table table-striped table-bordered'>
                        <thead>

                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div><!-- card-body -->

            <div class="card-footer">
                <div class="float-right">
                </div><!-- float-right -->
            </div><!-- card-footer -->
        </div><!-- card -->
    </div><!-- col -->
</div><!-- row -->
 