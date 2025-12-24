<?php

namespace backend\controllers;

use Yii;
use backend\models\ProfilSupplier;
use backend\models\ProfilSupplierSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * ProfilSupplierController implements the CRUD actions for ProfilSupplier model.
 */
class ProfilSupplierController extends Controller
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
     * Lists all ProfilSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfilSupplierSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);

        //
        /**
        $dataProvider->setSort([
            'defaultOrder' => [
                'nama_plant' => SORT_ASC
            ]
        ]);
        **/

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfilSupplier model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProfilSupplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfilSupplier();
        $model->id_plant = \Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                $message = '';
                foreach ($model->getErrors() as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $message .= $value2 . '<br>';
                    }
                }
                Yii::$app->session->setFlash('danger', "Tidak dapat menambah data.<br>" . $message);
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
     * Updates an existing ProfilSupplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProfilSupplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProfilSupplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfilSupplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfilSupplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
        $searchModel = new ProfilSupplierSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /**
        $dataProvider->setSort([
             'defaultOrder' => ['profil_plant.nama_plant' => SORT_ASC],
        ]);
        **/
        /**
        dataProvider->setSort([
            'defaultOrder' => [
                'nama_plant' => SORT_ASC
            ]
        ]);
        **/
        $dataProvider->setPagination(false);

        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
            ->setLastModifiedBy("Anggara Jauhari")
            ->setTitle("Export File Profile Supplier")
            ->setSubject("Export")
            ->setKeywords("Export")
            ->setCategory("Export");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        // create header
        $sheet->setCellValueByColumnAndRow(1, 1, 'No.');
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $sheet->setCellValueByColumnAndRow(2, 1, "Nama");
            $sheet->setCellValueByColumnAndRow(3, 1, "Telepon");
            $sheet->setCellValueByColumnAndRow(4, 1, "Alamat");
            $sheet->setCellValueByColumnAndRow(5, 1, "Provinsi");
            $sheet->setCellValueByColumnAndRow(6, 1, "Kota/Kabupaten");
            $sheet->setCellValueByColumnAndRow(7, 1, "Kecamatan");
            $sheet->setCellValueByColumnAndRow(8, 1, "Kelurahan");
        } else {
            $sheet->setCellValueByColumnAndRow(2, 1, "Plant");
            $sheet->setCellValueByColumnAndRow(3, 1, "Nama");
            $sheet->setCellValueByColumnAndRow(4, 1, "Telepon");
            $sheet->setCellValueByColumnAndRow(5, 1, "Alamat");
            $sheet->setCellValueByColumnAndRow(6, 1, "Provinsi");
            $sheet->setCellValueByColumnAndRow(7, 1, "Kota/Kabupaten");
            $sheet->setCellValueByColumnAndRow(8, 1, "Kecamatan");
            $sheet->setCellValueByColumnAndRow(9, 1, "Kelurahan");
        }


        // tolong dilanjutkan ya

        $row = 2;
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $sheet->setCellValueByColumnAndRow(1, $row, $no);
            if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
                $sheet->setCellValueByColumnAndRow(2, $row, $item->nama);
                $sheet->setCellValueByColumnAndRow(3, $row, $item->telepon);
                $sheet->setCellValueByColumnAndRow(4, $row, $item->alamat);
                $sheet->setCellValueByColumnAndRow(5, $row, $item->provinsi->nama);
                $sheet->setCellValueByColumnAndRow(6, $row, $item->kotaKabupaten->nama);
                $sheet->setCellValueByColumnAndRow(7, $row, $item->kecamatan->nama);
                $sheet->setCellValueByColumnAndRow(8, $row, $item->kelurahan->nama);
            } else {
                $sheet->setCellValueByColumnAndRow(2, $row, $item->plant->plant->nama_plant);
                $sheet->setCellValueByColumnAndRow(3, $row, $item->nama);
                $sheet->setCellValueByColumnAndRow(4, $row, $item->telepon);
                $sheet->setCellValueByColumnAndRow(5, $row, $item->alamat);
                $sheet->setCellValueByColumnAndRow(6, $row, $item->provinsi->nama);
                $sheet->setCellValueByColumnAndRow(7, $row, $item->kotaKabupaten->nama);
                $sheet->setCellValueByColumnAndRow(8, $row, $item->kecamatan->nama);
                $sheet->setCellValueByColumnAndRow(9, $row, $item->kelurahan->nama);
            }

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

    public function actionLaporanBulanan()
    {
        $model = new ProfilSupplierSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $model->id_plant = \Yii::$app->user->id;
        }
        if ($model->load(Yii::$app->request->get())) {
            $dataProvider = $model->search(Yii::$app->request->queryParams);
            $dataProvider->setPagination(false);
            /**
            $dataProvider->setSort([
                 'defaultOrder' => ['profil_plant.nama_plant' => SORT_ASC],
            ]);
            **/
            return $this->render('view_laporan', [
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        } else {
            return $this->render('kriteria', [
                'model' => $model,
            ]);
        }
    }

    public function actionExportLaporan()
    {
        $searchModel = new ProfilSupplierSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);

        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
            ->setLastModifiedBy("Anggara Jauhari")
            ->setTitle("Export File Profile Supplier")
            ->setSubject("Export")
            ->setKeywords("Export")
            ->setCategory("Export");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        // create header
        $sheet->setCellValueByColumnAndRow(1, 1, 'No.');
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $sheet->setCellValueByColumnAndRow(2, 1, "Nama");
            $sheet->setCellValueByColumnAndRow(3, 1, "Telepon");
            $sheet->setCellValueByColumnAndRow(4, 1, "Alamat");
            $sheet->setCellValueByColumnAndRow(5, 1, "Provinsi");
            $sheet->setCellValueByColumnAndRow(6, 1, "Kota/Kabupaten");
            $sheet->setCellValueByColumnAndRow(7, 1, "Kecamatan");
            $sheet->setCellValueByColumnAndRow(8, 1, "Kelurahan");
        } else {
            $sheet->setCellValueByColumnAndRow(2, 1, "Plant");
            $sheet->setCellValueByColumnAndRow(3, 1, "Telepon");
            $sheet->setCellValueByColumnAndRow(4, 1, "Nama");
            $sheet->setCellValueByColumnAndRow(5, 1, "Alamat");
            $sheet->setCellValueByColumnAndRow(6, 1, "Provinsi");
            $sheet->setCellValueByColumnAndRow(7, 1, "Kota/Kabupaten");
            $sheet->setCellValueByColumnAndRow(8, 1, "Kecamatan");
            $sheet->setCellValueByColumnAndRow(9, 1, "Kelurahan");
        }


        // tolong dilanjutkan ya

        $row = 2;
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $sheet->setCellValueByColumnAndRow(1, $row, $no);
            if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
                $sheet->setCellValueByColumnAndRow(2, $row, $item->nama);
                $sheet->setCellValueByColumnAndRow(3, $row, $item->telepon);
                $sheet->setCellValueByColumnAndRow(4, $row, $item->alamat);
                $sheet->setCellValueByColumnAndRow(5, $row, $item->provinsi->nama);
                $sheet->setCellValueByColumnAndRow(6, $row, $item->kotaKabupaten->nama);
                $sheet->setCellValueByColumnAndRow(7, $row, $item->kecamatan->nama);
                $sheet->setCellValueByColumnAndRow(8, $row, $item->kelurahan->nama);
            } else {
                $sheet->setCellValueByColumnAndRow(2, $row, $item->plant->plant->nama_plant);
                $sheet->setCellValueByColumnAndRow(3, $row, $item->telepon);
                $sheet->setCellValueByColumnAndRow(4, $row, $item->nama);
                $sheet->setCellValueByColumnAndRow(5, $row, $item->alamat);
                $sheet->setCellValueByColumnAndRow(6, $row, $item->provinsi->nama);
                $sheet->setCellValueByColumnAndRow(7, $row, $item->kotaKabupaten->nama);
                $sheet->setCellValueByColumnAndRow(8, $row, $item->kecamatan->nama);
                $sheet->setCellValueByColumnAndRow(9, $row, $item->kelurahan->nama);
            }

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