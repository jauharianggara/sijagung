<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\components\Mimin;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

<?php
  //$stringTitle = strtr($generator->generateString('Update ' .Inflector::camel2words(StringHelper::basename($generator->modelClass)) .': {nameAttribute}', ['nameAttribute' => '{nameAttribute}']), ['\'{nameAttribute}\'' => '$model->' . $generator->getNameAttribute()]) ;
  $stringTitle = 'View ' .Inflector::camel2words(StringHelper::basename($generator->modelClass));
  $stringTitle .= ': $model->'.$generator->getNameAttribute();
  /**
  $stringTitle = strtr(
    $generator->generateString('Update ' .Inflector::camel2words(StringHelper::basename($generator->modelClass)) .': {nameAttribute}',
    ['nameAttribute' => '{nameAttribute}']),
    ['\'{nameAttribute}\'' => '$model->' . $generator->getNameAttribute()]
  ) ;
  **/

?>
$this->title = "<?= $stringTitle ?>";
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $model-><?= $generator->getNameAttribute()?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border">

                </div><!-- card-header -->

                <div class="card-body">
                    <?= "<?= " ?>DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                <?php
                                    if (($tableSchema = $generator->getTableSchema()) === false) {
                                        foreach ($generator->getColumnNames() as $name) {
                                            echo "            '" . $name . "',\n";
                                        }
                                    } else {
                                        foreach ($generator->getTableSchema()->columns as $column) {
                                            $hasId = strpos($column->name, 'id_');
                                            if($hasId === false && $column->name != 'id' && $column->name != 'idUserAdd' && $column->name != 'idUserEdit'  && $column->name != 'userAdd'  && $column->name != 'userEdit' ){
                                            $format = $generator->generateColumnFormat($column);
                                            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                                            }
                                        }

                                        $foreignKeys = $generator->getTableSchema()->foreignKeys;//returns array of tbl schema's
                                        foreach ($foreignKeys as $key => $value) {
                                        $tableName='';
                                        $columName='';
                                        $column='';
                                        foreach ($value as $key2 => $value2) {
                                            if($key2 == '0'){
                                            $tableName = $value2;
                                            }else{
                                            $column = $key2;
                                            $columName = $value2;
                                            //echo " column ".$key2.' value '.$value2.'<br>';
                                            }
                                        }

                                        if($column != 'idUserAdd' && $column != 'userAdd' && $column != 'idUserEdit' && $column != 'userEdit'){
                                            $tableName = Inflector::variablize($tableName);
                                            echo "\t\t\t[
                                        \t\t\t\t'attribute'=>'".$tableName.".nama',
                                        \t\t\t\t'label'=>".$generator->generateString(Inflector::camel2words(StringHelper::basename($tableName))).",
                                        \t\t\t],\n";
                                        }

                                        if($column == 'idUserAdd' || $column =='userAdd'){
                                            $tableName = Inflector::variablize($tableName);
                                            echo "\t\t\t[
                                        \t\t\t\t'attribute'=>'userAdd.username',
                                        \t\t\t\t'label'=>'User Add',
                                        \t\t\t],\n";
                                        }

                                        if($column == 'idUserEdit' || $column == 'userEdit'){
                                            $tableName = Inflector::variablize($tableName);
                                            echo "\t\t\t[
                                        \t\t\t\t'attribute'=>'userEdit.username',
                                        \t\t\t\t'label'=>'User Edit',
                                        \t\t\t],\n";
                                        }
                                        }
                                    }



                                ?>
                        ],
                    ]) ?>
                </div><!-- card-body -->

                <div class="card-footer">
                    <?= "<?= " ?>Html::a('<i class="fa fa-angle-double-left"></i> Kembali', ['index'], ['class' => 'btn waves-effect waves-light btn-secondary']) ?>
                    <div class="float-right">
                        <?= "<?php " ?>
                        if ((Mimin::checkRoute($this->context->id . '/update'))) {
                            echo Html::a(
                                '<i class=\'fa fa-edit\'></i> Ubah',
                                ['update','id'=>$model->id],
                                [
                                    'class' => 'btn waves-effect waves-light btn-primary'
                                ]
                            ) . ' ';
                        }

                        if ((Mimin::checkRoute($this->context->id . '/delete'))) {
                            echo Html::a(
                                '<i class=\'fa fa-trash\'></i> Hapus',
                                ['delete','id'=>$model->id],
                                [
                                    'class' => 'btn waves-effect waves-light btn-danger',
                                    'data' => [
                                        'confirm' => 'Apakah anda yakin akan menghapus data ini? data yang sudah dihapus tidak dapat dikembalikan.',
                                        'method' => 'post',
                                    ],
                                ],
                            ) . ' ';
                        }
                        ?>
                    </div><!-- float-right -->
                </div><!-- card-footer -->
            </div><!-- card -->
        </div><!-- col -->
    </div><!-- row -->
</div>
