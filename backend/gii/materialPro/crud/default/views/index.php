<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\db\Schema;
use yii\db\TableSchema;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();



echo "<?php\n";
?>

use yii\helpers\Html;

use hscstudio\mimin\components\Mimin;

use <?= $generator->indexWidgetType === 'grid' ? "backend\\components\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
<?php
  //$model = $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))));
  $modelName = $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass)));
?>
$this->title = <?= $modelName ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <?= "<?php\n"?>
                    if ((Mimin::checkRoute($this->context->id . '/create'))) {
                        echo Html::a(
                            '<i class=\'fa fa-plus\'></i> Tambah',
                            ['create'],
                            [
                                'class' => 'btn waves-effect waves-light btn-primary'
                            ]
                        ) . ' ';
                    }
                    if ((Mimin::checkRoute($this->context->id . '/export'))) {
                        $urlGet =  http_build_query(Yii::$app->request->queryParams);
                        echo Html::a(
                            '<i class=\'fa fa-download\'></i> Export',
                            ['export?' . $urlGet],
                            [
                                'class' => 'btn waves-effect waves-light btn-secondary'
                            ]
                        ) . ' ';
                    }
                <?= "?>\n"?>
            </div><!-- card-header -->
            <div class="card-body">
                <div class="table-responsive">
                <?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No.',
                ],
                [
                    'options' => [
                        'width' => '160px',
                    ],
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => Mimin::filterActionColumn([
                        'view', 'update', 'delete'
                    ], $this->context->route),
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                '<span class="fa fa-eye"></span>',
                                $url,
                                [
                                    'title' => Yii::t('app', 'Detail'),
                                    'class' => 'btn btn-outline-secondary',
                                    'target' => '_self',
                                ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="fa fa-edit"></span>',
                                $url,
                                [
                                    'title' => Yii::t('app', 'Ubah'),
                                    'class' => 'btn btn-outline-primary',
                                    'target' => '_self',
                                ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span class="fa fa-trash"></span>',
                                $url,
                                [
                                    'title' => Yii::t('app', 'Hapus'),
                                    'class' => 'btn btn-outline-danger',
                                    'data' => [
                                        'confirm' => 'Hapus data?.',
                                        'method' => 'post',
                                    ],
                                ]
                            );
                        },
                    ],
                ],

                <?php
            $count = 0;
            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                    $hasId = strpos($name, 'id_');
                    if (++$count < 6) {
                      if($hasId === false && $name != 'id' && $name != 'idUserAdd' && $name != 'idUserEdit'  && $name != 'userAdd' && $name != 'dateAdd' && $name != 'userEdit' && $name != 'dateEdit' ){
                        echo "            '" . $name . "',\n";
                      }
                    } else {
                      if($hasId === false && $name != 'id' && $name != 'idUserAdd' && $name != 'idUserEdit'  && $name != 'userAdd' && $name != 'dateAdd' && $name != 'userEdit' && $name != 'dateEdit'){
                        echo "            // '" . $name . "',\n";
                      }
                    }
                }
            } else {
                $foreignKeys = $tableSchema->foreignKeys;//returns array of tbl schema's
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
                  if($column != 'idUserAdd' && $column != 'idUserEdit' && $column != 'userAdd' && $column != 'userEdit'){
                    $tableName = Inflector::variablize($tableName);
                    echo "\t\t\t[
  \t\t\t\t'attribute'=>'".$tableName.".nama',
  \t\t\t\t'header'=>".$generator->generateString(Inflector::camel2words(StringHelper::basename($tableName))).",
  \t\t\t],\n";
                  }
                }

                foreach ($tableSchema->columns as $column) {
                    $hasId = strpos($column->name, 'id_');
                    $format = $generator->generateColumnFormat($column);
                    //print_r($format);
                    if (++$count < 6) {
                      if($hasId === false && $column->name != 'id' && $column->name != 'idUserAdd' && $column->name != 'idUserEdit'  && $column->name != 'userAdd' && $column->name != 'dateAdd' && $column->name != 'userEdit' && $column->name != 'dateEdit'){
                        switch ($format) {
                            case 'integer':
                            case 'double': // Schema::TYPE_DOUBLE, which is available since Yii 2.0.3
                                echo "\t\t\t[
\t\t\t\t'attribute'=>'".$column->name."',
\t\t\t\t'format'=>'integer',
\t\t\t\t'contentOptions' => ['class' => 'text-right'],
\t\t\t],\n";
                                break;
                            case 'date':
                                echo "\t\t\t[
\t\t\t\t'attribute'=>'".$column->name."',
\t\t\t\t'format'=>['date', 'php:d F Y'],
\t\t\t],\n";
                                break;
                            default: // strings
                                echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";

                        }
                    }
                }else {
                  if($hasId === false && $column->name != 'id' && $column->name != 'idUserAdd' && $column->name != 'idUserEdit'  && $column->name != 'userAdd' && $column->name != 'dateAdd' && $column->name != 'userEdit' && $column->name != 'dateEdit'){
                    switch ($format) {
                        case 'integer':
                        case 'double': // Schema::TYPE_DOUBLE, which is available since Yii 2.0.3
                            echo "\t\t\t /**\n\t\t\t[\n
\t\t\t\t'attribute'=>'".$column->name."',
\t\t\t\t'format'=>'integer',
\t\t\t\t'contentOptions' => ['class' => 'text-right'],
\t\t\t],\n \t\t\t**/ \n";
                            break;
                        case 'date':
                            echo "\t\t\t/**\t\t\t/[\n
\t\t\t\t'attribute'=>'".$column->name."',
\t\t\t\t'format'=>['date', 'php:d F Y'],
\t\t\t],\n \t\t\t**/ \n";
                            break;
                        default: // strings
                            echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";

                    }
                }
              }
              }
            }
            ?>

        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>
                </div><!-- table-responsive -->
            </div><!-- card-body -->
            <div class="card-footer">

            </div><!-- card-footer -->
        </div><!-- card -->
    <div><!-- col -->
</div><!-- row -->

