<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$reportClass = StringHelper::basename($generator->reportClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->reportClass, '\\') ?>;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $reportClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * Lists all <?= $reportClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new <?= $reportClass ?>();
        if ($model->load(Yii::$app->request->get())) {
            $data = $model->getData(Yii::$app->request->queryParams);
            return $this->render('report', [
                'model' => $model,
                'data' => $data,
            ]);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    } 


    public function actionExport()
    {
        $model = new <?= $reportClass ?>();
        $dataProvider = $model->getData(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);

      $spreadsheet = new Spreadsheet();
      // Set document properties
      $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
         ->setLastModifiedBy("Anggara Jauhari")
         ->setTitle("Export File <?= $modelClass ?>:")
         ->setSubject("Export")
         ->setKeywords("Export")
         ->setCategory("Export");

      // Add some data
      $spreadsheet->setActiveSheetIndex(0);
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('data');
      // create header
      $sheet->setCellValueByColumnAndRow(1,1, 'No.');

      <?php
      $col = 1;
      //print_r($generator->getColumnNames());
      foreach ($generator->getColumnNames() as $attribute) {
          $col++;
          if($attribute != 'userAdd' && $attribute != 'idUserAdd' && $attribute != 'dateAdd' && $attribute != 'userEdit' && $attribute != 'idUserEdit' && $attribute != 'dateEdit'){
                echo "\t";
                echo '  $sheet->setCellValueByColumnAndRow('.$col.',1, "'.$attribute.'");';
                echo "\n";
          }
      }
      ?>
      $row=2;
      $no =1;
      foreach($dataProvider->getModels() as $item){
        $sheet->setCellValueByColumnAndRow(1,$row, $no);
          <?php
          $col = 1;
          foreach ($generator->getColumnNames() as $attribute) {
              $col++;
              if($attribute != 'userAdd' && $attribute != 'idUserAdd' && $attribute != 'dateAdd' && $attribute != 'userEdit' && $attribute != 'idUserEdit' && $attribute != 'dateEdit'){
                    echo "\t";
                    echo '  $sheet->setCellValueByColumnAndRow('.$col.',$row, $item->'.$attribute.');';
                    echo "\n";
              }
          }
          ?>
        $row++;
        $no++;
      }

      // Redirect output to a client’s web browser (Xlsx)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="export.xlsx"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
      exit();
    }
}
