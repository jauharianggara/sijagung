<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\AdminKotaKabupaten;
use backend\models\AdminProvinsi;
use backend\models\ProfilPlant;
use backend\models\ProfilPlantJenisProduksi;
use backend\models\ProfilPlantSearch;
use backend\models\InputDataPembelian;
use backend\models\InputDataStok;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\mimin\models\AuthAssignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * ProfilPlantController implements the CRUD actions for ProfilPlant model.
 */
class ProfilPlantController extends Controller
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
     * Lists all ProfilPlant models.
     * @return mixed
     */
    public function actionIndex()
    {
      $searchModel = new ProfilPlantSearch();
      if(\Yii::$app->authManager-> getAssignment('plant',\Yii::$app->user->id)){
        return $this->redirect(['update', 'id' => \Yii::$app->user->identity->plant->id]);
      }elseif(\Yii::$app->authManager-> getAssignment('adminprovinsi',\Yii::$app->user->id)){
        $adminProvinsi  = AdminProvinsi::find()->where(['id_user'=>\Yii::$app->user->id])->one();
        if($adminProvinsi != null){
          $searchModel->ip_provinsi = $adminProvinsi->id_provinsi;
        }else{
          $searchModel->ip_provinsi = 0;
        }
      }elseif(\Yii::$app->authManager-> getAssignment('adminkotakabupaten',\Yii::$app->user->id)){
        $adminKotaKabupaten  = AdminKotaKabupaten::find()->where(['id_user'=>\Yii::$app->user->id])->one();
        if($adminKotaKabupaten != null){
          $searchModel->ip_provinsi = $adminKotaKabupaten->id_provinsi;
          $searchModel->ip_kota = $adminKotaKabupaten->id_kota_kabupaten;
        }else{
          $searchModel->ip_provinsi = 0;
          $searchModel->ip_kota = 0;
        }
      }

      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $dataProvider->setSort([
          'defaultOrder' => [
              'nama_plant' => SORT_ASC
          ]
      ]);
      return $this->render('index', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
      ]);


    }

    public function actionMap()
    {

      $bulan1 = (int)date('m', strtotime('-2 months'));
      $bulan2 = (int)date('m', strtotime('-1 months'));
      $bulan3 = (int)date('m');

      $tahun1 = date('Y', strtotime('-2 months'));
      $tahun2 = date('Y', strtotime('-1 months'));
      $tahun3 = date('Y');

      $bulanLabel1 = date('F', strtotime('-2 months'));
      $bulanLabel2 = date('F', strtotime('-1 months'));
      $bulanLabel3 = date('F');

      $pembelian1 = InputDataPembelian::find()->where(["bulan"=>$bulan1,"tahun"=>$tahun1])->sum('quantity_terima');
      $pembelian2 = InputDataPembelian::find()->where(["bulan"=>$bulan2,"tahun"=>$tahun2])->sum('quantity_terima');
      $pembelian3 = InputDataPembelian::find()->where(["bulan"=>$bulan3,"tahun"=>$tahun3])->sum('quantity_terima');

      $stok1 = InputDataStok::find()->where(["bulan"=>$bulan1,"tahun"=>$tahun1])->sum('jumlah_stok');
      $stok2 = InputDataStok::find()->where(["bulan"=>$bulan2,"tahun"=>$tahun2])->sum('jumlah_stok');
      $stok3 = InputDataStok::find()->where(["bulan"=>$bulan3,"tahun"=>$tahun3])->sum('jumlah_stok');


      $searchModel = new ProfilPlantSearch();
      $searchModel->status = 1;
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $dataProvider->setSort([
          'defaultOrder' => [
              'nama_plant' => SORT_ASC
          ]
      ]);
      $dataProvider->setPagination(false);
      return $this->render('map', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          "pembelian1"=>$pembelian1,
          "pembelian2"=>$pembelian2,
          "pembelian3"=>$pembelian3,
          "stok1"=>$stok1,
          "stok2"=>$stok2,
          "stok3"=>$stok3,
      ]);
    }

    /**
     * Displays a single ProfilPlant model.
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
     * Creates a new ProfilPlant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfilPlant();
        $model->scenario = $model::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post())) {
            $model->kode_plant = 0;
			      $user = User::find()->where(['email'=>$model->email])->one();
            if($user == null){
              $user  = new User();
              $user->username = $model->username;
              $user->nama = $model->nama_plant;
              $user->setPassword('1234567');
              $user->email = $model->email;
              $user->status = 10;
              $user->generateAuthKey();
              $user->generatePasswordResetToken();
              if($user->save()){
				        $model->id_user =$user->id;
                $model->save();
                $authAssignment = new AuthAssignment();
                $authAssignment->item_name='plant';
                $authAssignment->user_id=$user->id;
                $date = date_create();
                $authAssignment->created_at=date_timestamp_get($date);
                if(!$authAssignment->save()){
                  print_r($authAssignment->getErrors());
                  exit();
                }else{
                  $model->kode_plant = $model->id;
                  $model->save();
                }
              }else{
                print_r($user->getErrors());
                exit();
              }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing ProfilPlant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(\Yii::$app->authManager-> getAssignment('plant',\Yii::$app->user->id)){
			$model = ProfilPlant::find()->where(['id_user'=>\Yii::$app->user->id])->one();
		}else{
			$model = $this->findModel($id);
		}
        
        $model->scenario = $model::SCENARIO_UPDATE;
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
              ProfilPlantJenisProduksi::deleteAll(['id_profil_plant' => $id]);
              if ( $model->jenisPorduksi != null) {
                foreach($model->jenisPorduksi as $item){
                  $jenisProduksi = new ProfilPlantJenisProduksi();
                  $jenisProduksi->id_profil_plant = $model->id;
                  $jenisProduksi->id_jenis_produksi = $item;
                  $jenisProduksi->save();
                }
              }
              Yii::$app->session->setFlash('success', 'Update success');
              if(\Yii::$app->authManager-> getAssignment('plant',\Yii::$app->user->id)){
                return $this->render('update', [
                    'model' => $model,
                ]);
              }else{
                return $this->redirect(['index']);
              }
            }else{
              $error = implode ( "<br />" , \yii\helpers\ArrayHelper::getColumn ( $model->errors , 0 , false ) ) ;
              Yii::$app->session->setFlash('error', $error);
              return $this->render('update', [
                  'model' => $model,
              ]);
            }
        } else {
            //print_r($model->getErrors());
            foreach($model->profilPlantJenisProduksi as $item){
              $model->jenisPorduksi[] = $item->id_jenis_produksi;
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProfilPlant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        ProfilPlantJenisProduksi::deleteAll(['id_profil_plant' => $id]);
        return $this->redirect(['index']);
    }

    public function actionExport()
    {
      $searchModel = new ProfilPlantSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $dataProvider->setSort([
          'defaultOrder' => [
              'nama_plant' => SORT_ASC
          ]
      ]);
      $dataProvider->setPagination(false);

      $spreadsheet = new Spreadsheet();
      // Set document properties
      $spreadsheet->getProperties()->setCreator("Anggara Jauhari")
         ->setLastModifiedBy("Anggara Jauhari")
         ->setTitle("Export File Data Profile Plant")
         ->setSubject("KEMENTAN")
         ->setKeywords("KEMENTAN")
         ->setCategory("KEMENTAN");

      // Add some data
      $spreadsheet->setActiveSheetIndex(0);
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setTitle('data');
      // create header
      $sheet->setCellValue('A1', 'No.');
      $sheet->setCellValue('B1', 'Nama Perusahaan');
      $sheet->setCellValue('C1', 'Provinsi');
      $sheet->setCellValue('D1', 'Kota / Kabupaten');
      $sheet->setCellValue('E1', 'Kecamatan');
      $sheet->setCellValue('F1', 'Kelurahan');
      $sheet->setCellValue('G1', 'Jalan');
      $sheet->setCellValue('H1', 'Longitude');
      $sheet->setCellValue('I1', 'Latitude');
      $sheet->setCellValue('J1', 'Telepon');
      $sheet->setCellValue('K1', 'Fax');
      $sheet->setCellValue('L1', 'Email');
      $sheet->setCellValue('M1', 'Kapasitas Terpasang');
      $sheet->setCellValue('N1', 'Jenis Pakan yang Diproduksi');
      $sheet->setCellValue('O1', 'Ketersediaan Silo');
      $sheet->setCellValue('P1', 'Kapasitas Silo');
      $sheet->setCellValue('Q1', 'Ketersediaan Dryer');
      $sheet->setCellValue('R1', 'Kapasitas Dryer');
      $sheet->setCellValue('S1', 'Nama Lengkap');
      $sheet->setCellValue('T1', 'Telpon');
      $sheet->setCellValue('U1', 'Email');
      // tolong dilanjutkan ya

      $row=2;
      $no =1;
      foreach($dataProvider->getModels() as $item){

          $sheet->setCellValue('A'.$row, $no);
          $sheet->setCellValue('B'.$row, $item->nama_plant);
          $sheet->setCellValue('C'.$row, $item->provinsi->nama);
          $sheet->setCellValue('D'.$row, $item->kota->nama);
          $sheet->setCellValue('E'.$row, $item->kecamatan->nama);

          $sheet->setCellValue('F'.$row, $item->kelurahan->nama);
          $sheet->setCellValue('G'.$row, $item->ip_jalan);
          $sheet->setCellValue('H'.$row, $item->longitude);
          $sheet->setCellValue('I'.$row, $item->latitude);
          $sheet->setCellValue('J'.$row, $item->ip_telepon);
          $sheet->setCellValue('K'.$row, $item->ip_fax);
          $sheet->setCellValue('L'.$row, $item->ip_email);
          $sheet->setCellValue('M'.$row, $item->kapasitas_terpasang);

		      $jenisProduksi ='';
          foreach($item->profilPlantJenisProduksi as $subItem){
            $jenisProduksi .= $subItem->jenisProduksi->nama.", ";
          }
          if($jenisProduksi != ''){
            $jenisProduksi = substr($jenisProduksi, 0, -2);
          }else{
            $jenisProduksi = '-';
          }

          $sheet->setCellValue('N'.$row, $jenisProduksi);

          $sheet->setCellValue('O'.$row, $item->silo);

          $sheet->setCellValue('P'.$row, $item->kapasitas_silo);

          $sheet->setCellValue('Q'.$row, $item->dryer);

          $sheet->setCellValue('R'.$row, $item->kapasitas);

          $sheet->setCellValue('S'.$row, $item->nama_lengkap);
          $sheet->setCellValue('T'.$row, $item->telpon);
          $sheet->setCellValue('U'.$row, $item->email);

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
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
      exit();
    }

    /**
     * Finds the ProfilPlant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfilPlant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfilPlant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList($id)
    {
        $data = \backend\models\ProfilPlant::find()
        ->where(['ip_provinsi' => $id])
        ->orderBy('nama_plant ASC')
        ->all();

        if (!empty($data)) {
          echo "<option>-- Pilih Plant --</option>";
          foreach($data as $item) {
            echo "<option value='".$item->id."'>".$item->nama_plant."</option>";
          }
        } else {
          echo "<option>-</option>";
        }

    }
}
