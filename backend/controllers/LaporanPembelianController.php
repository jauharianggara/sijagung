<?php

namespace backend\controllers;

use Yii;

use backend\models\LaporanPembelian;
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
class LaporanPembelianController extends Controller
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
    $searchModel = new LaporanPembelian();
    if ($searchModel->load(Yii::$app->request->get())) {
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
          'tahun' => SORT_ASC,
          'bulan' => SORT_ASC,
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
    $searchModel = new LaporanPembelian();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->setPagination(false);
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
        'provinsi_plant' => [
          'asc' => ['provinsi.nama' => SORT_ASC],
          'desc' => ['provinsi.nama' => SORT_DESC],
        ],
      ],
      /**
      'defaultOrder' => [
        'tahun' => SORT_ASC,
        'bulan' => SORT_ASC,
        'nama_plant' => SORT_ASC,
        'provinsi_plant' => SORT_ASC,
        'provinsi_gabungan_plant' => SORT_ASC,
        'pulau_plant' => SORT_ASC,
      ]
       */
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
    $sheet->setCellValueByColumnAndRow($col, $row, 'Kode Bulan');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Bulan');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Nama Plant');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Provinsi Gabungan');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Pulau');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Harga KA');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Quantity Terima');
    $col++;
    $sheet->setCellValueByColumnAndRow($col, $row, 'Harga Terima');
    $col++;

    $isAdmin = \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id);
    $isDirjen = \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id);
    $isAdminProvinsi = \Yii::$app->authManager->getAssignment('adminProvinsi', \Yii::$app->user->id);
    $isAdminKotaKabupaten = \Yii::$app->authManager->getAssignment('adminKotaKabupaten', \Yii::$app->user->id);

    if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
      $sheet->setCellValueByColumnAndRow($col, $row, 'Konversi Quantity Terima 15%');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Konversi Harga Terima 15%');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Konversi Quantity Terima 14%');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Konversi Harga Terima  14%');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'KA Terima');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Asal Jagung');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Pemasok');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Last Edited');
      $col++;
    } else {
      $sheet->setCellValueByColumnAndRow($col, $row, 'KA Terima');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Asal Jagung');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Pemasok');
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, 'Last Edited');
      $col++;
    }
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
      $sheet->setCellValueByColumnAndRow($col, $row, $item->plant->nama_plant);
      $col++;
      $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->ip_provinsi.'-'.$item->plant->provinsi->nama);
      $col++;
      $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_provinsi_gabungan.'-'.$item->plant->provinsi->provinsiGabungan->nama);
      $col++;
      $sheet->setCellValueByColumnAndRow($col,$row, $item->plant->provinsi->id_pulau.'-'.$item->plant->provinsi->pulau->nama);
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, $item->harga_ka);
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, $item->quantity_terima);
      $col++;
      $sheet->setCellValueByColumnAndRow($col, $row, $item->harga_terima);
      $col++;

      if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten) {
        $sheet->setCellValueByColumnAndRow($col, $row, $item->konversiVolume);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->konversiHarga);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->konversiVolume14);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->konversiHarga14);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->ka_terima);
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
        $sheet->setCellValueByColumnAndRow($col, $row, $provinsi);
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
        $sheet->setCellValueByColumnAndRow($col, $row, $pemasok);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->dateEdit);
        $col++;

      } else {
        $sheet->setCellValueByColumnAndRow($col, $row, $item->ka_terima);
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
        $sheet->setCellValueByColumnAndRow($col, $row, $provinsi);
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
        $sheet->setCellValueByColumnAndRow($col, $row, $pemasok);
        $col++;
        $sheet->setCellValueByColumnAndRow($col, $row, $item->dateEdit);
        $col++;

      }



      // tolong dilanjutkan ya

      $row++;
      $no++;
    }

    // Redirect output to a client’s web browser (Xlsx)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan Pembelian.xlsx"');
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
