<?php

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;

use backend\models\DataRasioPenggunaan;
use yii\data\ActiveDataProvider;
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

setlocale(LC_TIME, 'id_ID.UTF-8');
/**
 * CollectorController implements the CRUD actions for Collector model.
 */
class RasioPenggunaanController extends Controller
{
    /**
     * {@inheritdoc}
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

    public function actionIndex()
    {
        $searchModel = new DataRasioPenggunaan();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }
        if (!$searchModel->load(Yii::$app->request->get())) {

            $tanggal_awal = new \DateTime();
            $tanggal_awal->modify('-1 month');

            $tanggal_akhir = new \DateTime();
            $tanggal_akhir->modify('-1 month');

            $searchModel->bulan_awal = (int) $tanggal_awal->format('m');
            $searchModel->tahun_awal = (int) $tanggal_awal->format('Y');

            $searchModel->bulan_akhir = (int) $tanggal_akhir->format('m');
            $searchModel->tahun_akhir = (int) $tanggal_akhir->format('Y');
        }

        $data = $searchModel->getData(Yii::$app->request->queryParams);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

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
                    'asc' => ['nama_plant' => SORT_ASC],
                    'desc' => ['nama_plant' => SORT_DESC],
                ],
                'provinsi_plant' => [
                    'asc' => ['provinsi_plant' => SORT_ASC],
                    'desc' => ['provinsi_plant' => SORT_DESC],
                ],
                'provinsi_gabungan_plant' => [
                    'asc' => ['provinsi_gabungan_plant' => SORT_ASC],
                    'desc' => ['provinsi_gabungan_plant' => SORT_DESC],
                ],
                'pulau_plant' => [
                    'asc' => ['pulau_plant' => SORT_ASC],
                    'desc' => ['pulau_plant' => SORT_DESC],
                ],
                'penggunaan' => [
                    'asc' => ['penggunaan' => SORT_ASC],
                    'desc' => ['penggunaan' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
                'nama_plant' => SORT_ASC,
                'provinsi_plant' => SORT_ASC,
                'provinsi_gabungan_plant' => SORT_ASC,
                'pulau_plant' => SORT_ASC,
                'penggunaan' => SORT_ASC,
            ]
        ]);

        if (!$searchModel->load(Yii::$app->request->get())) {

            $tanggal_awal = new \DateTime();
            $tanggal_awal->modify('-1 month');

            $tanggal_akhir = new \DateTime();
            $tanggal_akhir->modify('-1 month');

            $searchModel->bulan_awal = (int) $tanggal_awal->format('m');
            $searchModel->tahun_awal = (int) $tanggal_awal->format('Y');

            $searchModel->bulan_akhir = (int) $tanggal_akhir->format('m');
            $searchModel->tahun_akhir = (int) $tanggal_akhir->format('Y');
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        $isAdmin = \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id);
        $isDirjen = \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id);
        $isAdminProvinsi = \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id);
        $isAdminKotaKabupaten = \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id);


        $searchModel = new DataPenggunaan();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->id;
        }
        if (!$searchModel->load(Yii::$app->request->get())) {

            $tanggal_awal = new \DateTime();
            $tanggal_awal->modify('-1 month');

            $tanggal_akhir = new \DateTime();
            $tanggal_akhir->modify('-1 month');

            $searchModel->bulan_awal = (int) $tanggal_awal->format('m');
            $searchModel->tahun_awal = (int) $tanggal_awal->format('Y');

            $searchModel->bulan_akhir = (int) $tanggal_akhir->format('m');
            $searchModel->tahun_akhir = (int) $tanggal_akhir->format('Y');
        }

        $data = $searchModel->getData(Yii::$app->request->queryParams);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

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
                    'asc' => ['nama_plant' => SORT_ASC],
                    'desc' => ['nama_plant' => SORT_DESC],
                ],
                'provinsi_plant' => [
                    'asc' => ['provinsi_plant' => SORT_ASC],
                    'desc' => ['provinsi_plant' => SORT_DESC],
                ],
                'provinsi_gabungan_plant' => [
                    'asc' => ['provinsi_gabungan_plant' => SORT_ASC],
                    'desc' => ['provinsi_gabungan_plant' => SORT_DESC],
                ],
                'pulau_plant' => [
                    'asc' => ['pulau_plant' => SORT_ASC],
                    'desc' => ['pulau_plant' => SORT_DESC],
                ],
                'penggunaan' => [
                    'asc' => ['penggunaan' => SORT_ASC],
                    'desc' => ['penggunaan' => SORT_DESC],
                ],
            ],
            'defaultOrder' => [
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
                'nama_plant' => SORT_ASC,
                'provinsi_plant' => SORT_ASC,
                'provinsi_gabungan_plant' => SORT_ASC,
                'pulau_plant' => SORT_ASC,
                'penggunaan' => SORT_ASC,
            ]
        ]);

        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
            ->setLastModifiedBy("Anggara Jauhari")
            ->setTitle("Export File Data Penggunaan")
            ->setSubject("KEMENTAN")
            ->setKeywords("KEMENTAN")
            ->setCategory("KEMENTAN");

        // Add some data
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('data');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Laporan Proyeksi Pakan Jagung');
        //$sheet->mergeCells('A1:O1');
        //$sheet->mergeCells('A2:O2');
        //$sheet->mergeCells('A3:O3');

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
        if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
            $sheet->setCellValueByColumnAndRow($col, $row, 'Nama Plant');
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi');
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi Gabungan');
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, 'Pulau');
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, $row, 'Stok (Tok)');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Penggunaan (Ton)');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Rasio Penggunaan (%)');
        $col++;
        $row++;
        // tolong dilanjutkan ya
        $no = 1;
        foreach ($dataProvider->getModels() as $item) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col, $row, $no);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item['tahun']);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item['bulan']);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item['bulan_label']);
            $col++;
            if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
                $sheet->setCellValueByColumnAndRow($col, $row, $item['nama_plant']);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row, $item['id_provinsi_plant'].'-'.$item['provinsi_plant']);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row,  $item['id_provinsi_gabungan'].'-'.$item['provinsi_gabungan_plant']);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row,  $item['id_pulau'].'-'.$item['pulau_plant']);
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $item['stok']);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item['penggunaan']);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item['rasioPenggunaan']);
            $col++;
            $no++;
            $row++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_data_penggunaan.xlsx"');
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



}
