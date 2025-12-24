<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminKotaKabupaten;
use backend\models\AdminProvinsi;
use backend\models\InputDataStok;
use backend\models\InputDataStokSearch;
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
 * InputDataStokController implements the CRUD actions for InputDataStok model.
 */
class InputDataStokController extends Controller
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
     * Lists all InputDataStok models.
     * @return mixed
     */
    public function actionIndex()
    {
        $datetime1 = new \DateTime('NOW');
        $datetime2 = new \DateTime(\Yii::$app->user->identity->plant->dateEdit);
        $bulanSekarang = (int) $datetime1->format('m');
        $bulanEdit = (int) $datetime2->format('m');
        if ($bulanSekarang > 6) {
            $datetime3 = new \DateTime($datetime1->format('Y') . '-05-01');
            if ($datetime3 > $datetime2) {
                return $this->render('info_update_profile_plant');
            }
        } else {
            $tahun = $datetime1->format('Y') - 1;
            $datetime3 = new \DateTime($tahun . '-11-01');
            if ($datetime3 > $datetime2) {
                return $this->render('info_update_profile_plant');
            }
        }

        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            // jika belum update data profile plant, maka masuk ke halaman info
            if (strtotime(\Yii::$app->user->identity->plant->dateEdit) < strtotime('2022-12-01')) {
                return $this->render('info_update_profile_plant');
            }
        }

        $searchModel = new InputDataStokSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        } elseif (\Yii::$app->authManager->getAssignment('adminprovinsi', \Yii::$app->user->id)) {
            $adminProvinsi = AdminProvinsi::find()->where(['id_user' => \Yii::$app->user->id])->one();
            if ($adminProvinsi != null) {
                $searchModel->provinsi_plant = $adminProvinsi->id_provinsi;
            } else {
                $searchModel->provinsi_plant = 0;
            }
        } elseif (\Yii::$app->authManager->getAssignment('adminkotakabupaten', \Yii::$app->user->id)) {
            $adminKotaKabupaten = AdminKotaKabupaten::find()->where(['id_user' => \Yii::$app->user->id])->one();
            if ($adminKotaKabupaten != null) {
                $searchModel->provinsi_plant = $adminKotaKabupaten->id_provinsi;
                $searchModel->kota_kabupaten_plant = $adminKotaKabupaten->id_kota_kabupaten;
            } else {
                $searchModel->provinsi_plant = 0;
                $searchModel->kota_kabupaten_plant = 0;
            }
        } else {

        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'tahun' => [
                    'asc' => ['tahun' => SORT_ASC],
                    'desc' => ['tahun' => SORT_DESC],
                ],
                'bulan' => [
                    'asc' => ['bulan' => SORT_ASC],
                    'desc' => ['bulan' => SORT_DESC],
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
                'bulan' => SORT_DESC,
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
     * Displays a single InputDataStok model.
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
     * Creates a new InputDataStok model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            // jika belum update data profile plant, maka masuk ke halaman info
            if (strtotime(\Yii::$app->user->identity->plant->dateEdit) < strtotime('2022-04-01')) {
                return $this->render('info_update_profile_plant');
            }
        }

        $model = new InputDataStok();
        $model->scenario = $model::SCENARIO_CREATE;
        $model->id_plant = \Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            $dataStok = InputDataStok::find()->where([
                'tahun' => $model->tahun,
                'bulan' => $model->bulan,
                'id_plant' => $model->id_plant,
            ])->one();
            if ($dataStok == null) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Data berhasil ditambah.");
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
                Yii::$app->session->setFlash('danger', "Data stok pada bulan terpilih sudah pernah dientry.<br>");
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
     * Updates an existing InputDataStok model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', "Data berhasil diubah.");
                return $this->redirect(['index']);
            } else {
                $message = '';
                foreach ($model->getErrors() as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $message .= $value2 . '<br>';
                    }
                }
                Yii::$app->session->setFlash('danger', "Tidak dapat mengubah data.<br>" . $message);
                return $this->render('update', [
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
     * Deletes an existing InputDataStok model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExport()
    {
        $searchModel = new InputDataStokSearch();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        } elseif (\Yii::$app->authManager->getAssignment('adminprovinsi', \Yii::$app->user->id)) {
            $adminProvinsi = AdminProvinsi::find()->where(['id_user' => \Yii::$app->user->id])->one();
            if ($adminProvinsi != null) {
                $searchModel->provinsi_plant = $adminProvinsi->id_provinsi;
            } else {
                $searchModel->provinsi_plant = 0;
            }
        } elseif (\Yii::$app->authManager->getAssignment('adminkotakabupaten', \Yii::$app->user->id)) {
            $adminKotaKabupaten = AdminKotaKabupaten::find()->where(['id_user' => \Yii::$app->user->id])->one();
            if ($adminKotaKabupaten != null) {
                $searchModel->provinsi_plant = $adminKotaKabupaten->id_provinsi;
                $searchModel->kota_kabupaten_plant = $adminKotaKabupaten->id_kota_kabupaten;
            } else {
                $searchModel->provinsi_plant = 0;
                $searchModel->kota_kabupaten_plant = 0;
            }
        } else {

        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
            'attributes' => [
                'tahun' => [
                    'asc' => ['tahun' => SORT_ASC],
                    'desc' => ['tahun' => SORT_DESC],
                ],
                'bulan' => [
                    'asc' => ['bulan' => SORT_ASC],
                    'desc' => ['bulan' => SORT_DESC],
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
                'bulan' => SORT_DESC,
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
            ->setTitle("Export File Data Stok")
            ->setSubject("KEMENTAN")
            ->setKeywords("KEMENTAN")
            ->setCategory("KEMENTAN");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        // create header
        $row = 1;
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col, $row, 'No.');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Tahun');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kode Bulan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Bulan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Nama Plant');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Provinsi Gabungan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Pulau');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Jumlah Stok');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kecukupan Produksi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Last Edited');
        // tolong dilanjutkan ya

        $row = 2;
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col, $row, $no);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->tahun);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->bulan);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->bulanLabel);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->nama_plant);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->ip_provinsi.'-'.$item->plant->provinsi->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_provinsi_gabungan.'-'.$item->plant->provinsi->provinsiGabungan->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_pulau.'-'.$item->plant->provinsi->pulau->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->jumlah_stok);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kecukupan_produksi);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->dateEdit);
            // tolong dilanjutkan ya

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
        //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Finds the InputDataStok model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InputDataStok the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InputDataStok::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
