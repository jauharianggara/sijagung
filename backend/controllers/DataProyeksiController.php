<?php

namespace backend\controllers;

use Yii;
use backend\models\DataProyeksi;
use backend\models\DataProyeksiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * DataProyeksiController implements the CRUD actions for DataProyeksi model.
 */
class DataProyeksiController extends Controller
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
     * Lists all DataProyeksi models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            // jika belum update data profile plant, maka masuk ke halaman info
            if (strtotime(\Yii::$app->user->identity->plant->dateEdit) < strtotime('2022-12-01')) {
                return $this->render('info_update_profile_plant');
            }
        }

        $searchModel = new DataProyeksiSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }

        $dataProvider = $searchModel->searchIndex(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'tahun' => [
                    'asc' => ['tahun' => SORT_ASC],
                    'desc' => ['tahun' => SORT_DESC],
                ],
                'nama_plant' => [
                    'asc' => ['profil_plant.nama_plant' => SORT_ASC],
                    'desc' => ['profil_plant.nama_plant' => SORT_DESC],
                ],
                'kode_provinsi' => [
                    'asc' => ['profil_plant.ip_provinsi' => SORT_ASC],
                    'desc' => ['profil_plant.ip_provinsi' => SORT_DESC],
                ],
                'provinsi_plant' => [
                    'asc' => ['provinsi.nama' => SORT_ASC],
                    'desc' => ['provinsi.nama' => SORT_DESC],
                ],
                'provinsi_gabungan_plant' => [
                    'asc' => ['provinsi_gabungan.nama' => SORT_ASC],
                    'desc' => ['provinsi_gabungan.nama' => SORT_DESC],
                ],
                'pulau_plant' => [
                    'asc' => ['pulau.nama' => SORT_ASC],
                    'desc' => ['pulau.nama' => SORT_DESC],
                ],
                'dateAdd' => [
                    'asc' => ['dateAdd' => SORT_ASC],
                    'desc' => ['dateAdd' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'tahun' => SORT_DESC,
                'kode_provinsi' => SORT_ASC,
                'provinsi_plant' => SORT_ASC,
                'provinsi_gabungan_plant' => SORT_ASC,
                'pulau_plant' => SORT_ASC,
                'nama_plant' => SORT_ASC,
                'dateAdd' => SORT_DESC,
            ]
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataProyeksi model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($tahun, $id_plant)
    {
        $data = DataProyeksi::find()->where(['tahun' => $tahun, 'id_plant' => $id_plant])->orderBy(['bulan' => SORT_ASC])->all();
        $model = $data[0];
        return $this->render('view', [
            'model' => $model,
            'data' => $data,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Creates a new DataProyeksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataProyeksi();
        $model->id_plant = \Yii::$app->user->id;
        $model->tahun = date('Y') + 1;
        if ($model->load(Yii::$app->request->post())) {
            $dataOld = DataProyeksi::find()->where(['tahun' => $model->tahun, 'id_plant' => $model->id_plant])->one();
            if ($dataOld) {
                Yii::$app->session->addFlash(
                    'danger',
                    'Data duplikat, silakan gunakan fasilitas ubah data untuk memperbaharui data pada tahun ' . $model->tahun . '.'
                );
                return $this->redirect('create');
            }
            DataProyeksi::deleteAll(['tahun' => $model->tahun, 'id_plant' => $model->id_plant]);

            $detail = Yii::$app->request->post('detail');
            for ($i = 1; $i <= 12; $i++) {
                $item = $detail[$i];
                $proyeksi = new DataProyeksi();
                $proyeksi->tahun = $model->tahun;
                $proyeksi->id_plant = $model->id_plant;
                $proyeksi->bulan = $i;
                $proyeksi->volume = $item['volume'];
                $proyeksi->kadar_air_persen = $item['kadar_air_persen'];

                if (!$proyeksi->save()) {
                    print_r($proyeksi->getErrors());
                }
            }

            return $this->redirect(['view', 'tahun' => $model->tahun, 'id_plant' => $model->id_plant]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DataProyeksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($tahun, $id_plant)
    {
        $data = DataProyeksi::find()
            ->where([
                'tahun' => $tahun,
                'id_plant' => $id_plant
            ])
            ->orderBy([
                'bulan' => SORT_ASC
            ])
            ->all();
        $model = $data[0];
        if ($model->load(Yii::$app->request->post())) {
            DataProyeksi::deleteAll(['tahun' => $model->tahun, 'id_plant' => $model->id_plant]);
            $detail = Yii::$app->request->post('detail');
            for ($i = 1; $i <= 12; $i++) {
                $item = $detail[$i];
                $proyeksi = new DataProyeksi();
                $proyeksi->tahun = $model->tahun;
                $proyeksi->id_plant = $model->id_plant;
                $proyeksi->bulan = $i;
                $proyeksi->volume = $item['volume'];
                $proyeksi->kadar_air_persen = $item['kadar_air_persen'];

                if (!$proyeksi->save()) {
                    print_r($proyeksi->getErrors());
                }
            }

            return $this->redirect(['view', 'tahun' => $model->tahun, 'id_plant' => $model->id_plant]);

        } else {
            return $this->render('update', [
                'data' => $data,
                'tahun' => $tahun,
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DataProyeksi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($tahun, $id_plant)
    {
        DataProyeksi::deleteAll(['tahun' => $tahun, 'id_plant' => $id_plant]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the DataProyeksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DataProyeksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataProyeksi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport()
    {
        $searchModel = new DataProyeksiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'tahun' => [
                    'asc' => ['tahun' => SORT_ASC],
                    'desc' => ['tahun' => SORT_DESC],
                ],
                'nama_plant' => [
                    'asc' => ['profil_plant.nama_plant' => SORT_ASC],
                    'desc' => ['profil_plant.nama_plant' => SORT_DESC],
                ],
                'kode_provinsi' => [
                    'asc' => ['profil_plant.ip_provinsi' => SORT_ASC],
                    'desc' => ['profil_plant.ip_provinsi' => SORT_DESC],
                ],
                'provinsi_plant' => [
                    'asc' => ['provinsi.nama' => SORT_ASC],
                    'desc' => ['provinsi.nama' => SORT_DESC],
                ],
                'provinsi_gabungan_plant' => [
                    'asc' => ['provinsi_gabungan.nama' => SORT_ASC],
                    'desc' => ['provinsi_gabungan.nama' => SORT_DESC],
                ],
                'pulau_plant' => [
                    'asc' => ['pulau.nama' => SORT_ASC],
                    'desc' => ['pulau.nama' => SORT_DESC],
                ],
                'dateAdd' => [
                    'asc' => ['dateAdd' => SORT_ASC],
                    'desc' => ['dateAdd' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'tahun' => SORT_DESC,
                'kode_provinsi' => SORT_ASC,
                'provinsi_plant' => SORT_ASC,
                'provinsi_gabungan_plant' => SORT_ASC,
                'pulau_plant' => SORT_ASC,
                'nama_plant' => SORT_ASC,
                'dateAdd' => SORT_DESC,
            ]
        ]);
        $dataProvider->setPagination(false);

        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
            ->setLastModifiedBy("Anggara Jauhari")
            ->setTitle("Export File DataProyeksi:")
            ->setSubject("Export")
            ->setKeywords("Export")
            ->setCategory("Export");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        // create header
        $row = 1;
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col, $row, 'No.');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "id");
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "Nama Plant");
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Provinsi Gabungan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Pulau');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "tahun");
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "bulan");
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "volume");
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, "kadar_air_persen");

        $row++;
        $row = 2;
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col, $row, $no);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->id);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->plant->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->id_provinsi.'-'.$item->plant->plant->provinsi->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->plant->provinsi->id_provinsi_gabungan.'-'.$item->plant->plant->provinsi->provinsiGabungan->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->plant->provinsi->id_pulau.'-'.$item->plant->plant->provinsi->pulau->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->tahun);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->bulan);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->volume);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kadar_air_persen);
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

    public function actionTotalProyeksi($id_plant, $tahun){
        $proyeksi = new DataProyeksi();
        $proyeksi->id_plant = $id_plant;
        $proyeksi->tahun = $tahun;
        return $proyeksi->total14TahunIni;
    }
}
