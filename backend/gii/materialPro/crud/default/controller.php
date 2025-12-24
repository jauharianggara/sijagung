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
$searchModelClass = StringHelper::basename($generator->searchModelClass);
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
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
              Yii::$app->session->setFlash('success', "Data berhasil ditambah.");
              return $this->redirect(['index']);
            }else{
              $message = '';
              foreach ($model->getErrors() as $key => $value) {
                foreach ($value as $key2 => $value2) {
                  $message .= $value2.'<br>';
                }
              }
              Yii::$app->session->setFlash('danger', "Tidak dapat menambah data.<br>".$message);
              return $this->render('create', [
                  'model' => $model,
              ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load(Yii::$app->request->post())) {
          if($model->save()){
            Yii::$app->session->setFlash('success', "Data berhasil diubah.");
            return $this->redirect(['index']);
          }else{
            $message = '';
            foreach ($model->getErrors() as $key => $value) {
              foreach ($value as $key2 => $value2) {
                $message .= $value2.'<br>';
              }
            }
            Yii::$app->session->setFlash('danger', "Tidak dapat mengubah data.<br>".$message);
            return $this->render('create', [
                'model' => $model,
            ]);
          }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
      $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      /**
      dataProvider->setSort([
          'defaultOrder' => [
              'nama' => SORT_ASC
          ]
      ]);
      **/
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
