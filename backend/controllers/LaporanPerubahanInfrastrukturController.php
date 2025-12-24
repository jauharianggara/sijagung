<?php

namespace backend\controllers;

use Yii;

use backend\models\LaporanPerubahanInfrastruktur;
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

/**
 * CollectorController implements the CRUD actions for Collector model.
 */
class LaporanPerubahanInfrastrukturController extends Controller
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
        $searchModel = new LaporanPerubahanInfrastruktur();
        if ($searchModel->load(Yii::$app->request->get())) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->setSort([
                'attributes' => [
                    'tanggal' => [
                        'asc' => ['tanggal' => SORT_ASC],
                        'desc' => ['tanggal' => SORT_DESC],
                    ],
                    'tahun' => [
                        'asc' => ['tahun' => SORT_ASC],
                        'desc' => ['tahun' => SORT_DESC],
                    ],
                    'semester' => [
                        'asc' => ['semester' => SORT_ASC],
                        'desc' => ['semester' => SORT_DESC],
                    ],
                    'nama_plant' => [
                        'asc' => ['profil_plant.nama_plant' => SORT_ASC],
                        'desc' => ['profil_plant.nama_plant' => SORT_DESC],
                    ],
                    'kode_provinsi' => [
                        'asc' => ['provinsi.id' => SORT_ASC],
                        'desc' => ['provinsi.id' => SORT_DESC],
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
                ], 
                'defaultOrder' => [
                    'tanggal' => SORT_DESC,
                    'tahun' => SORT_DESC,
                    'semester' => SORT_DESC,
                    'nama_plant' => SORT_ASC,
                    'kode_provinsi' => SORT_ASC,
                    'provinsi_plant' => SORT_ASC,
                    'provinsi_gabungan_plant' => SORT_ASC,
                    'pulau_plant' => SORT_ASC,
                ]
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
        $searchModel = new LaporanPerubahanInfrastruktur();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(false);
        $dataProvider->setSort([
            'attributes' => [
                'tanggal' => [
                    'asc' => ['tanggal' => SORT_ASC],
                    'desc' => ['tanggal' => SORT_DESC],
                ],
                'tahun' => [
                    'asc' => ['tahun' => SORT_ASC],
                    'desc' => ['tahun' => SORT_DESC],
                ],
                'semester' => [
                    'asc' => ['semester' => SORT_ASC],
                    'desc' => ['semester' => SORT_DESC],
                ],
                'nama_plant' => [
                    'asc' => ['profil_plant.nama_plant' => SORT_ASC],
                    'desc' => ['profil_plant.nama_plant' => SORT_DESC],
                ],
                'kode_provinsi' => [
                    'asc' => ['provinsi.id' => SORT_ASC],
                    'desc' => ['provinsi.id' => SORT_DESC],
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
            ], 
            'defaultOrder' => [
                'tanggal' => SORT_DESC,
                'tahun' => SORT_DESC,
                'semester' => SORT_DESC,
                'nama_plant' => SORT_ASC,
                'kode_provinsi' => SORT_ASC,
                'provinsi_plant' => SORT_ASC,
                'provinsi_gabungan_plant' => SORT_ASC,
                'pulau_plant' => SORT_ASC,
            ]
        ]);
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
        $row = 1;
        $col = 1;
        $sheet->setCellValueByColumnAndRow($col, $row, 'No.');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Tahun');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Semester');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Nama Plant');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi Gabungan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Pulau');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kapasitas Silo');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kapasitas Dryer');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Kapasitas Produksi');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Jenis Produksi Pakan');
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, 'Date Edit');
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
            $sheet->setCellValueByColumnAndRow($col, $row, $item->semester);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->nama_plant);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->ip_provinsi . '-' . $item->plant->provinsi->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->provinsi->id_provinsi_gabungan . '-' . $item->plant->provinsi->provinsiGabungan->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->provinsi->id_pulau . '-' . $item->plant->provinsi->pulau->nama);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kapasitas_silo_sekarang);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kapasitas_dryer_sekarang);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->kapasitas_produksi_sekarang);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->jenis_produksi_pakan);
            $col++;
            $sheet->setCellValueByColumnAndRow($col, $row, $item->dateEdit);
            $col++;


            // tolong dilanjutkan ya

            $row++;
            $no++;
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan Perubahan Infrastruktur.xlsx"');
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
