<?php

namespace backend\controllers;

use Yii;
use backend\models\ProfilPlantHistory;
use backend\models\ProfilPlantHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * ProfilPlantHistoryController implements the CRUD actions for ProfilPlantHistory model.
 */
class ProfilPlantHistoryController extends Controller
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
     * Lists all ProfilPlantHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfilPlantHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfilPlantHistory model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProfilPlantHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfilPlantHistory();

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
     * Updates an existing ProfilPlantHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Deletes an existing ProfilPlantHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProfilPlantHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProfilPlantHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfilPlantHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
      $searchModel = new ProfilPlantHistorySearch();
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
         ->setTitle("Export File ProfilPlantHistory:")
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
	  $sheet->setCellValueByColumnAndRow(3,1, "id_plant");
	  $sheet->setCellValueByColumnAndRow(4,1, "tanggal");
	  $sheet->setCellValueByColumnAndRow(9,1, "kapasitas_silo_sebelumnya");
	  $sheet->setCellValueByColumnAndRow(10,1, "kapasitas_silo_sekarang");
	  $sheet->setCellValueByColumnAndRow(11,1, "kapasitas_dryer_sebelumnya");
	  $sheet->setCellValueByColumnAndRow(12,1, "kapasitas_dryer_sekarang");
	  $sheet->setCellValueByColumnAndRow(13,1, "kapasitas_produksi_sebelumnya");
	  $sheet->setCellValueByColumnAndRow(14,1, "kapasitas_produksi_sekarang");
      $row=2;
      $no =1;
      foreach($dataProvider->getModels() as $item){
        $sheet->setCellValueByColumnAndRow(1,$row, $no);
          	  $sheet->setCellValueByColumnAndRow(2,$row, $item->id);
	  $sheet->setCellValueByColumnAndRow(3,$row, $item->id_plant);
	  $sheet->setCellValueByColumnAndRow(4,$row, $item->tanggal);
	  $sheet->setCellValueByColumnAndRow(9,$row, $item->kapasitas_silo_sebelumnya);
	  $sheet->setCellValueByColumnAndRow(10,$row, $item->kapasitas_silo_sekarang);
	  $sheet->setCellValueByColumnAndRow(11,$row, $item->kapasitas_dryer_sebelumnya);
	  $sheet->setCellValueByColumnAndRow(12,$row, $item->kapasitas_dryer_sekarang);
	  $sheet->setCellValueByColumnAndRow(13,$row, $item->kapasitas_produksi_sebelumnya);
	  $sheet->setCellValueByColumnAndRow(14,$row, $item->kapasitas_produksi_sekarang);
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
