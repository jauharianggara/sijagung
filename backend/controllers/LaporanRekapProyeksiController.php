<?php

namespace backend\controllers;

use Yii;

use backend\models\LaporanRekapProyeksi;
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
class LaporanRekapProyeksiController extends Controller
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
    $searchModel = new LaporanRekapProyeksi();
    if ($searchModel->load(Yii::$app->request->get())) {
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
    $searchModel = new LaporanRekapProyeksi();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
    $sheet->setCellValueByColumnAndRow(1, 2, 'Kadar Air 14% (Ton)');
    $sheet->setCellValueByColumnAndRow(1, 3, 'Tahun ' . $searchModel->tahun);
    $sheet->mergeCells('A1:O1');
    $sheet->mergeCells('A2:O2');
    $sheet->mergeCells('A3:O3');

    // create header
    $row = 5;
    $sheet->setCellValue('A' . $row, 'No.');
    if ($searchModel->wilayah == 1) {
      $sheet->setCellValue('B' . $row, 'Nama Pulau');
    } elseif ($searchModel->wilayah == 2) {
      $sheet->setCellValue('B' . $row, 'Nama Provinsi');
    } elseif ($searchModel->wilayah == 3) {
      $sheet->setCellValue('B' . $row, 'Nama Provinsi');
    }else {
      $sheet->setCellValue('B' . $row, 'Nama Plant');
    }
    $col = 3;
    for ($i = 1; $i <= 12; $i++) {
      $monthName = strftime('%B', mktime(0, 0, 0, $i)); // March
      $sheet->setCellValueByColumnAndRow($col, $row, $monthName);
      $col++;
    }
    $sheet->setCellValueByColumnAndRow($col, $row, 'Total');

    // tolong dilanjutkan ya

    $row++;
    $firstRow = $row;
    $no = 1;
    foreach ($dataProvider as $item) {
      $sheet->setCellValue('A' . $row, $no);
      $sheet->setCellValue('B' . $row, $item['nama']);
      $col = 3;
      $startCol = 3;
      for ($i = 1; $i <= 12; $i++) {
        $sheet->setCellValueByColumnAndRow($col, $row, $item['bulan'][$i]['volume']);
        $col++;
      }
      $firstCol = Coordinate::stringFromColumnIndex($startCol);
      $lastCol = Coordinate::stringFromColumnIndex($col - 1);
      $sheet->setCellValueByColumnAndRow($col, $row, '=SUM(' . $firstCol . $row . ':' . $lastCol . $row . ')');

      $row++;
      $no++;
    }

    $lastRow = $row - 1;
    $col = 3;
    $sheet->setCellValueByColumnAndRow(2, $row, 'Jumlah');
    for ($i = 1; $i <= 13; $i++) {
      $curCol = Coordinate::stringFromColumnIndex($col); // ZERO-based!
      $sheet->setCellValueByColumnAndRow($col, $row, '=SUM(' . $curCol . $firstRow . ':' . $curCol . $lastRow . ')');
      $col++;
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
