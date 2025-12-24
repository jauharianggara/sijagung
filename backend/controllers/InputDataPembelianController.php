<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\AdminKotaKabupaten;
use backend\models\AdminProvinsi;
use backend\models\InputDataPembelian;
use backend\models\InputDataPembelianSumber;
use backend\models\InputDataPembelianSupplier;
use backend\models\InputDataPembelianSearch;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * InputDataPembelianController implements the CRUD actions for InputDataPembelian model.
 */
class InputDataPembelianController extends Controller
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
     * Lists all InputDataPembelian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $datetime1 = new \DateTime('NOW');
        $datetime2 = new \DateTime(\Yii::$app->user->identity->plant->dateEdit);
        $bulanSekarang = (int) $datetime1->format('m');
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

        $searchModel = new InputDataPembelianSearch();
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
     * Displays a single InputDataPembelian model.
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
     * Creates a new InputDataPembelian model.
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

        $model = new InputDataPembelian();
        $model->scenario = $model::SCENARIO_CREATE;
        $model->id_plant = \Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            $dataPembelian = InputDataPembelian::find()->where([
                'tahun' => $model->tahun,
                'bulan' => $model->bulan,
                'id_plant' => $model->id_plant,
            ])->one();
            if ($dataPembelian == null) {
                if ($model->save()) {
                    if (is_array($model->sumber)) {
                        foreach ($model->sumber as $item) {
                            $sumber = new InputDataPembelianSumber();
                            $sumber->id_input_data_pembelian = $model->id;
                            $sumber->id_provinsi = $item;
                            $sumber->save();
                        }
                    }

                    if (is_array($model->supplier)) {
                        foreach ($model->supplier as $item) {
                            $supplier = new InputDataPembelianSupplier();
                            $supplier->id_input_data_pembelian = $model->id;
                            $supplier->id_supplier = $item;
                            $supplier->save();
                        }
                    }
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
                    print_r($model->getErrors());
                    exit();
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('danger', "Data pembelian pada bulan terpilih sudah pernah dientry.<br>");
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            $dataPembelianTerakhir = InputDataPembelian::find()
                ->where([
                    'id_plant' => \Yii::$app->user->id
                ])
                ->orderBy(['id' => SORT_DESC])->one();
            if ($dataPembelianTerakhir->inputDataPembelianSuppliers) {
                foreach ($dataPembelianTerakhir->inputDataPembelianSuppliers as $item) {
                    $model->supplier[] = $item->id_supplier;
                }
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InputDataPembelian model.
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
                if (is_array($model->sumber)) {
                    InputDataPembelianSumber::deleteAll(['id_input_data_pembelian' => $id]);
                    foreach ($model->sumber as $item) {
                        $sumber = new InputDataPembelianSumber();
                        $sumber->id_input_data_pembelian = $model->id;
                        $sumber->id_provinsi = $item;
                        $sumber->save();
                    }
                }

                if (is_array($model->supplier)) {
                    foreach ($model->supplier as $item) {
                        $supplier = new InputDataPembelianSupplier();
                        $supplier->id_input_data_pembelian = $model->id;
                        $supplier->id_supplier = $item;
                        $supplier->save();
                    }
                }
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
            foreach ($model->inputDataPembelianSumber as $item) {
                $model->sumber[] = $item->id_provinsi;
            }
            foreach ($model->inputDataPembelianSuppliers as $item) {
                $model->supplier[] = $item->id_supplier;
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InputDataPembelian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        InputDataPembelianSumber::deleteAll(['id_input_data_pembelian' => $id]);
        InputDataPembelianSupplier::deleteAll(['id_input_data_pembelian' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExport()
    {
        $searchModel = new InputDataPembelianSearch();
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
            ->setTitle("Export File Data Pembelian")
            ->setSubject("KEMENTAN")
            ->setKeywords("KEMENTAN")
            ->setCategory("KEMENTAN");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        // create header
        $row = 1 ;
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col,$row, 'No.');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Tahun');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Kode Bulan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Bulan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Nama Plant');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Provinsi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Provinsi Gabungan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Pulau');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Harga KA');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Quantity Terima');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Harga Terima');
        $col++;

        $isAdmin = \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id);
        $isDirjen = \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id);
        $isAdminProvinsi = \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id);
        $isAdminKotaKabupaten = \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id);

        if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
            $sheet->setCellValueByColumnAndRow($col,$row, 'Konversi Quantity Terima 15%');
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, 'Konversi Harga Terima 15%');
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, 'Konversi Quantity Terima 14%');
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, 'Konversi Harga Terima  14%');
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col,$row, 'KA Terima');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Asal Jagung');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Pemasok');
        $col++;
        $sheet->setCellValueByColumnAndRow($col,$row, 'Last Edited');
        $col++;
        $row++;
        // tolong dilanjutkan ya
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col,$row, $no);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->tahun);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->bulan);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->bulanLabel);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->nama_plant);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->ip_provinsi.'-'.$item->plant->provinsi->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_provinsi_gabungan.'-'.$item->plant->provinsi->provinsiGabungan->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_pulau.'-'.$item->plant->provinsi->pulau->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->harga_ka);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->quantity_terima);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->harga_terima);
            $col++;

            if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
                $sheet->setCellValueByColumnAndRow($col,$row, $item->konversiVolume);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->konversiHarga);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->konversiVolume14);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->konversiHarga14);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->ka_terima);
                $col++;
                $provinsi = '';
                foreach ($item->inputDataPembelianSumber as $subItem) {
                    $provinsi .= $subItem->provinsi->nama . ", ";
                }
                if ($provinsi != '') {
                    $provinsi = substr($provinsi, 0, -2);
                } else {
                    $provinsi = '-';
                }
                $sheet->setCellValueByColumnAndRow($col,$row, $provinsi);
                $col++;
                $pemasok = '';
                foreach ($item->inputDataPembelianSuppliers as $subItem) {
                    $pemasok .= $subItem->supplier->nama . ", ";
                }
                if ($pemasok != '') {
                    $pemasok = substr($pemasok, 0, -2);
                } else {
                    $pemasok = '-';
                }
                $sheet->setCellValueByColumnAndRow($col,$row, $pemasok);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->dateEdit);
                $col++;

            }

            $sheet->setCellValueByColumnAndRow($col,$row, $item->ka_terima);
            $col++;
            $provinsi = '';
            foreach ($item->inputDataPembelianSumber as $subItem) {
                $provinsi .= $subItem->provinsi->nama . ", ";
            }
            if ($provinsi != '') {
                $provinsi = substr($provinsi, 0, -2);
            } else {
                $provinsi = '-';
            }
            $sheet->setCellValueByColumnAndRow($col,$row, $provinsi);
            $col++;
            $pemasok = '';
            foreach ($item->inputDataPembelianSuppliers as $subItem) {
                $pemasok .= $subItem->supplier->nama . ", ";
            }
            if ($pemasok != '') {
                $pemasok = substr($pemasok, 0, -2);
            } else {
                $pemasok = '-';
            }
            $sheet->setCellValueByColumnAndRow($col,$row, $pemasok);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row, $item->dateEdit);
            $col++;



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
     * Finds the InputDataPembelian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InputDataPembelian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InputDataPembelian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
