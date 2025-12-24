<?php

namespace backend\controllers;

use Yii;
use backend\models\Pulau;
use backend\models\PulauSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * PulauController implements the CRUD actions for Pulau model.
 */
class PulauController extends Controller
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
     * Lists all Pulau models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PulauSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pulau model.
     * @param string $id ID
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pulau model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pulau();

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
     * Updates an existing Pulau model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * Deletes an existing Pulau model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Pulau model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Pulau the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pulau::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
      $searchModel = new PulauSearch();
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
         ->setTitle("Export File Pulau:")
         ->setSubject("Export")
         ->setKeywords("Export")
         ->setCategory("Export");

      // Add some data
      $spreadsheet->setActiveSheetIndex(0);
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('data');
      // create header
      $sheet->setCellValueByColumnAndRow(1,1, 'No.');

      	  $sheet->setCellValueByColumnAndRow(2,1, "id");
	  $sheet->setCellValueByColumnAndRow(3,1, "nama");
	  $sheet->setCellValueByColumnAndRow(4,1, "center");
	  $sheet->setCellValueByColumnAndRow(5,1, "longitude");
	  $sheet->setCellValueByColumnAndRow(6,1, "latitude");
      $row=2;
      $no =1;
      foreach($dataProvider->getModels() as $item){
        $sheet->setCellValueByColumnAndRow(1,$row, $no);
          	  $sheet->setCellValueByColumnAndRow(2,$row, $item->id);
	  $sheet->setCellValueByColumnAndRow(3,$row, $item->nama);
	  $sheet->setCellValueByColumnAndRow(4,$row, $item->center);
	  $sheet->setCellValueByColumnAndRow(5,$row, $item->longitude);
	  $sheet->setCellValueByColumnAndRow(6,$row, $item->latitude);
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
