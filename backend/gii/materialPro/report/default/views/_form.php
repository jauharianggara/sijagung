<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

<?php
$tableSchema = $generator->getTableSchema();
$foreignKeys = $tableSchema->foreignKeys;//returns array of tbl schema's
foreach ($foreignKeys as $key => $value) {
  $tableName='';
  $columName='';
  $keyName='';
  foreach ($value as $key2 => $value2) {
    if($key2 == '0'){
      $tableName = $value2;
    }else{
      $columName = $value2;
      $keyName = $key2;
    }
  }
  $camelize = Inflector::camelize($tableName);
  echo "use backend\models\\".$camelize.";\n";
}
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile("@web/plugins/datepicker/bootstrap-datepicker.js", [
	'depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerCssFile("@web/plugins/datepicker/datepicker3.css",[
    'depends' => [BootstrapAsset::className()],
]);

$this->registerJs('

	(function($){
	$.fn.datepicker.dates["id"] = {
			days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
			daysShort: ["Mgu", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Mgu"],
			daysMin: ["Mg", "Sn", "Sl", "Ra", "Ka", "Ju", "Sa", "Mg"],
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
			today: "Hari Ini",
			clear: "Kosongkan"
		};
	}(jQuery));

	$(".tanggal").datepicker({
		language : \'id\',
		format: \'yyyy-mm-dd\',
		autoclose: true,
		clearBtn:true,
		todayHighlight:true,
		toggleActive:true,
  });

');
?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    $hasId = strpos($attribute, 'id_');
    if($hasId === false && $attribute != 'id' && $attribute != 'userAdd' && $attribute != 'idUserAdd' && $attribute != 'dateAdd' && $attribute != 'userEdit' && $attribute != 'idUserEdit' && $attribute != 'dateEdit'){
        //if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        //}
    }

    if($attribute == 'tanggal'){ 
            echo  "    <?= " ."\$form->field(\$model, 'tanggal_awal')->textInput(['class' => 'form-control tanggal'])". " ?>\n\n";
            echo  "    <?= " ."\$form->field(\$model, 'tanggal_akhir')->textInput(['class' => 'form-control tanggal'])". " ?>\n\n";
    }
} ?>

<?php
$tableSchema = $generator->getTableSchema();
$foreignKeys = $tableSchema->foreignKeys;//returns array of tbl schema's
foreach ($foreignKeys as $key => $value) {
  $tableName='';
  $columName='';
  $keyName='';
  foreach ($value as $key2 => $value2) {
    if($key2 == '0'){
      $tableName = $value2;
    }else{
      $columName = $value2;
      $keyName = $key2;
    }
  }
  $camelize = Inflector::camelize($tableName);
?>
  <?= " <?php \n" ?>
  $data<?= $camelize?> = ArrayHelper::map(<?= $camelize?>::find()->asArray()->all(),'id', 'nama');
  echo $form->field($model, '<?= $key2?>')->dropDownList(
    $data<?= $camelize?> ,
    [
      'id'=>'<?= $columName?>',
      'prompt'=>'Pilih <?=Inflector::camel2words(StringHelper::basename($tableName))?>',
    ])->label('<?=Inflector::camel2words(StringHelper::basename($tableName))?>');
  ?>
<?php
}
?>
