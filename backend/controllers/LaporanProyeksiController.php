<?php

namespace backend\controllers;

use Yii;

use backend\models\LaporanProyeksi;
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
class LaporanProyeksiController extends Controller
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
        $searchModel = new LaporanProyeksi();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->identity->plant->id;
        }
        //echo $searchModel->id_plant;
        if ($searchModel->load(Yii::$app->request->get())) {
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
                /**
                'defaultOrder' => [
                    'tahun' => SORT_DESC,
                    'kode_provinsi' => SORT_ASC,
                    'provinsi_plant' => SORT_ASC,
                    'provinsi_gabungan_plant' => SORT_ASC,
                    'pulau_plant' => SORT_ASC,
                    'nama_plant' => SORT_ASC,
                    'dateAdd' => SORT_DESC,
                ]
                 */
            ]);
            return $this->render('laporan', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('kriteria', [
                'model' => $searchModel,
            ]);
        }
    }

    public function actionExport()
    {
        $isAdmin = \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id);
        $isDirjen = \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id);
        $isAdminProvinsi = \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id);
        $isAdminKotaKabupaten = \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id);


        $searchModel = new LaporanProyeksi();
        if (\Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id)) {
            $searchModel->id_plant = \Yii::$app->user->identity->plant->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);

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
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
            ->setLastModifiedBy("Anggara Jauhari")
            ->setTitle("Export File Data Proyeksi")
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
        
        $sheet->setCellValueByColumnAndRow($col, $row, 'Volume Kebutuhan KA terima (Kg)');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kadar Air(%)');
        $col++;


        if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
            $sheet->setCellValueByColumnAndRow($col, $row, 'Konversi Volume 14 %');
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, $row, 'Last Edited');
        $col++;
        $row++;
        // tolong dilanjutkan ya
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
            if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
                $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->plant->nama_plant);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->plant->ip_provinsi.'-'.$item->plant->plant->provinsi->nama);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->plant->provinsi->id_provinsi_gabungan.'-'.$item->plant->plant->provinsi->provinsiGabungan->nama);
                $col++;
                $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->plant->provinsi->id_pulau.'-'.$item->plant->plant->provinsi->pulau->nama);
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $item->volume);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kadar_air_persen);
            $col++;
            if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
                $sheet->setCellValueByColumnAndRow($col, $row, $item->konversiVolume14);
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $item->dateEdit);
            $row++;
            $no++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_data_proyeksi.xlsx"');
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
