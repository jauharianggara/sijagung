<?php

use backend\gii\report\Generator;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\gii\generators\model\Generator $generator */

echo $form->field($generator, 'reportName');
echo $form->field($generator, 'reportClass');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
