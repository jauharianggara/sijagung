<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\InputDataPembelian;
use backend\models\InputDataStok;
use backend\models\InputDataPembelianSearch;
use backend\models\InputDataStokSearch;
use backend\models\Berita;
use backend\models\Dashboard;
use backend\models\DashboardUmum;
use backend\models\LandingPembelian;
use backend\models\LandingStok;
use backend\models\LandingProyeksi;

use yii\data\SqlDataProvider;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

setlocale(LC_TIME, 'id_ID.UTF-8');
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'awr', 'landing', 'data-pembelian', 'data-stok', 'data-proyeksi', 'data-pembelian-export', 'data-stok-export', 'data-proyeksi-export', 'absen-detail'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'ubah-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $isAdmin = \Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id);
        $isDirjen = \Yii::$app->authManager->getAssignment('dirjen', \Yii::$app->user->id);
        $isAdminProvinsi = \Yii::$app->authManager->getAssignment('adminprovinsi', \Yii::$app->user->id);
        $isAdminKotaKabupaten = \Yii::$app->authManager->getAssignment('adminkotakabupaten', \Yii::$app->user->id);
        $isPlant = \Yii::$app->authManager->getAssignment('plant', \Yii::$app->user->id);
        $isAdminAsosiasi = \Yii::$app->authManager->getAssignment('adminasosiasi', \Yii::$app->user->id);
        if ($isAdmin || $isDirjen || $isAdminProvinsi || $isAdminKotaKabupaten || $isPlant || $isAdminAsosiasi) {
            $dashboard = new Dashboard();
            $dataKonversiVolume14 = $dashboard->getDataKonversiVolume14(Yii::$app->request->queryParams);
            $dataStok = $dashboard->getDataStok(Yii::$app->request->queryParams);
            $dataKecukupan = $dashboard->getDataKecukupan(Yii::$app->request->queryParams);
            $dataHarga = $dashboard->getDataHarga(Yii::$app->request->queryParams);
            $dataAbsen = $dashboard->getAbsen(Yii::$app->request->queryParams);
            $dataRekapAbsen = $dashboard->getAbsenRekap(Yii::$app->request->queryParams);
            $dataVolumeTahunIni = $dashboard->getDataVolumeTahunIni(Yii::$app->request->queryParams);
            $dataProyeksiTahunIni = $dashboard->getDataProyeksiTahunIni(Yii::$app->request->queryParams);

            $dataProyeksi = $dashboard->getDataProyeksi(Yii::$app->request->queryParams);
            $dataPenggunaan = $dashboard->getDataPenggunaan(Yii::$app->request->queryParams);
            return $this->render(
                'index',
                [
                    "dataKonversiVolume14" => $dataKonversiVolume14,
                    "dataStok" => $dataStok,
                    "dataKecukupan" => $dataKecukupan,
                    "dataHarga" => $dataHarga,
                    "dataAbsen" => $dataAbsen,
                    "dataRekapAbsen" => $dataRekapAbsen,
                    "dataVolumeTahunIni" => $dataVolumeTahunIni,
                    "dataProyeksi" => $dataProyeksi,
                    "dataProyeksiTahunIni" => $dataProyeksiTahunIni,
                    "dataPenggunaan" => $dataPenggunaan,
                ]
            );
        }
    }



    public function actionAbsenDetail($tahun, $bulan, $jenis, $status)
    {
        $dashboard = new Dashboard();
        $dataAbsenDetail = $dashboard->getAbsenDetail($tahun, $bulan, $jenis, $status);
        return $this->render(
            'absen_detail',
            [
                "data" => $dataAbsenDetail,
                "tahun" => $tahun,
                "bulan" => $bulan,
                "jenis" => $jenis,
                "status" => $status,
            ]
        );
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $berita = Berita::find()->orderBy(['id' => SORT_DESC])->all();
            return $this->render('login', [
                'model' => $model,
                'berita' => $berita,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSendNotif()
    {
        // looping yang belum input data
        $minggu = $this->getWeeks(date('Y/m/d'), 'Sunday');
        //$minggu = 3;
        $sudahKirim = DataHargaPakan::find()->where([
            'tahun' => date('Y'),
            'bulan' => (int) date("m"),
            'minggu' => $minggu,
        ])
            ->groupBy(['id_plant'])
            ->all();

        $idSudahKirim = '';
        foreach ($sudahKirim as $item) {
            $idSudahKirim .= $item->plant->id . ", ";
        }
        if ($idSudahKirim != '') {
            $idSudahKirim = substr($idSudahKirim, 0, -2);
        } else {
            $idSudahKirim = '';
        }

        $belumKirim = \backend\models\AdminProvinsi::find()
            ->andWhere("telpon != ''")
            ->andWhere("trim(telpon) != '-'")
            ->andWhere("telpon IS NOT NULL")
            ->all();

        return $this->render('send_notif', [
            'model' => $belumKirim,
            'tahun' => date('Y'),
            'bulan' => date("F"),
            'minggu' => $minggu,
        ]);
    }


    public function actionLanding()
    {
        $this->layout = 'main-landing';
        $berita = Berita::find()->orderBy(['id' => SORT_DESC])->all();
        $dashboard = new DashboardUmum();
        $dataKonversiVolume14 = $dashboard->getDataKonversiVolume14(Yii::$app->request->queryParams);
        $dataStok = $dashboard->getDataStok(Yii::$app->request->queryParams);
        $dataKecukupan = $dashboard->getDataKecukupan(Yii::$app->request->queryParams);
        $dataHarga = $dashboard->getDataHarga(Yii::$app->request->queryParams);
        $dataProyeksi = $dashboard->getDataProyeksi(Yii::$app->request->queryParams);
        $dataVolumeTahunIni = $dashboard->getDataVolumeTahunIni(Yii::$app->request->queryParams);
        $dataProyeksiTahunIni = $dashboard->getDataProyeksiTahunIni(Yii::$app->request->queryParams);

        return $this->render(
            'landing',
            [
                "berita" => $berita,
                //"dataProvider"=>$dataProvider,
                "dataKonversiVolume14" => $dataKonversiVolume14,
                "dataStok" => $dataStok,
                "dataKecukupan" => $dataKecukupan,
                "dataHarga" => $dataHarga,
                "dataVolumeTahunIni" => $dataVolumeTahunIni,
                "dataProyeksi" => $dataProyeksi,
                "dataProyeksiTahunIni" => $dataProyeksiTahunIni,
            ]
        );

    }
    public function actionAwr()
    {
        $this->layout = "blank";
        $bulan1 = (int) date('m', strtotime('-11 months'));
        $bulan2 = (int) date('m', strtotime('-10 months'));
        $bulan3 = (int) date('m', strtotime('-9 months'));
        $bulan4 = (int) date('m', strtotime('-8 months'));
        $bulan5 = (int) date('m', strtotime('-7 months'));
        $bulan6 = (int) date('m', strtotime('-6 months'));
        $bulan7 = (int) date('m', strtotime('-5 months'));
        $bulan8 = (int) date('m', strtotime('-4 months'));
        $bulan9 = (int) date('m', strtotime('-3 months'));
        $bulan10 = (int) date('m', strtotime('-2 months'));
        $bulan11 = (int) date('m', strtotime('-1 months'));
        $bulan12 = (int) date('m');

        $tahun1 = date('Y', strtotime('-11 months'));
        $tahun2 = date('Y', strtotime('-10 months'));
        $tahun3 = date('Y', strtotime('-9 months'));
        $tahun4 = date('Y', strtotime('-8 months'));
        $tahun5 = date('Y', strtotime('-7 months'));
        $tahun6 = date('Y', strtotime('-6 months'));
        $tahun7 = date('Y', strtotime('-5 months'));
        $tahun8 = date('Y', strtotime('-4 months'));
        $tahun9 = date('Y', strtotime('-3 months'));
        $tahun10 = date('Y', strtotime('-2 months'));
        $tahun11 = date('Y', strtotime('-1 months'));
        $tahun12 = date('Y');

        $bulanLabel1 = date('F', strtotime('-11 months'));
        $bulanLabel2 = date('F', strtotime('-10 months'));
        $bulanLabel3 = date('F', strtotime('-9 months'));
        $bulanLabel4 = date('F', strtotime('-8 months'));
        $bulanLabel5 = date('F', strtotime('-7 months'));
        $bulanLabel6 = date('F', strtotime('-6 months'));
        $bulanLabel7 = date('F', strtotime('-5 months'));
        $bulanLabel8 = date('F', strtotime('-4 months'));
        $bulanLabel9 = date('F', strtotime('-3 months'));
        $bulanLabel10 = date('F', strtotime('-2 months'));
        $bulanLabel11 = date('F', strtotime('-1 months'));
        $bulanLabel12 = date('F');

        $sql = "SELECT COUNT(profil_plant.id) from profil_plant INNER JOIN user ON profil_plant.id_user=user.id;";
        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "SELECT
            profil_plant.id,
            profil_plant.nama_plant,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan1 . "' AND tahun='" . $tahun1 . "') AS pembelian1,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan2 . "' AND tahun='" . $tahun2 . "') AS pembelian2,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan3 . "' AND tahun='" . $tahun3 . "') AS pembelian3,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan4 . "' AND tahun='" . $tahun4 . "') AS pembelian4,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan5 . "' AND tahun='" . $tahun5 . "') AS pembelian5,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan6 . "' AND tahun='" . $tahun6 . "') AS pembelian6,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan7 . "' AND tahun='" . $tahun7 . "') AS pembelian7,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan8 . "' AND tahun='" . $tahun8 . "') AS pembelian8,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan9 . "' AND tahun='" . $tahun9 . "') AS pembelian9,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan10 . "' AND tahun='" . $tahun10 . "') AS pembelian10,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan11 . "' AND tahun='" . $tahun11 . "') AS pembelian11,
            (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND bulan='" . $bulan12 . "' AND tahun='" . $tahun12 . "') AS pembelian12,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan1 . "' AND tahun='" . $tahun1 . "') AS stok1,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan2 . "' AND tahun='" . $tahun2 . "') AS stok2,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan3 . "' AND tahun='" . $tahun3 . "') AS stok3,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan4 . "' AND tahun='" . $tahun4 . "') AS stok4,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan5 . "' AND tahun='" . $tahun5 . "') AS stok5,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan6 . "' AND tahun='" . $tahun6 . "') AS stok6,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan7 . "' AND tahun='" . $tahun7 . "') AS stok7,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan8 . "' AND tahun='" . $tahun8 . "') AS stok8,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan9 . "' AND tahun='" . $tahun9 . "') AS stok9,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan10 . "' AND tahun='" . $tahun10 . "') AS stok10,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan11 . "' AND tahun='" . $tahun11 . "') AS stok11,
            (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND bulan='" . $bulan12 . "' AND tahun='" . $tahun12 . "') AS stok12
            from profil_plant INNER JOIN user ON profil_plant.id_user=user.id
          ORDER BY profil_plant.nama_plant";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => false,
        ]);

        $pembelian1 = InputDataPembelian::find()->where(["bulan" => $bulan1, "tahun" => $tahun1])->sum('quantity_terima') / 1000;
        $pembelian2 = InputDataPembelian::find()->where(["bulan" => $bulan2, "tahun" => $tahun2])->sum('quantity_terima') / 1000;
        $pembelian3 = InputDataPembelian::find()->where(["bulan" => $bulan3, "tahun" => $tahun3])->sum('quantity_terima') / 1000;
        $pembelian4 = InputDataPembelian::find()->where(["bulan" => $bulan4, "tahun" => $tahun4])->sum('quantity_terima') / 1000;
        $pembelian5 = InputDataPembelian::find()->where(["bulan" => $bulan5, "tahun" => $tahun5])->sum('quantity_terima') / 1000;
        $pembelian6 = InputDataPembelian::find()->where(["bulan" => $bulan6, "tahun" => $tahun6])->sum('quantity_terima') / 1000;
        $pembelian7 = InputDataPembelian::find()->where(["bulan" => $bulan7, "tahun" => $tahun7])->sum('quantity_terima') / 1000;
        $pembelian8 = InputDataPembelian::find()->where(["bulan" => $bulan8, "tahun" => $tahun8])->sum('quantity_terima') / 1000;
        $pembelian9 = InputDataPembelian::find()->where(["bulan" => $bulan9, "tahun" => $tahun9])->sum('quantity_terima') / 1000;
        $pembelian10 = InputDataPembelian::find()->where(["bulan" => $bulan10, "tahun" => $tahun10])->sum('quantity_terima') / 1000;
        $pembelian11 = InputDataPembelian::find()->where(["bulan" => $bulan11, "tahun" => $tahun11])->sum('quantity_terima') / 1000;
        $pembelian12 = InputDataPembelian::find()->where(["bulan" => $bulan12, "tahun" => $tahun12])->sum('quantity_terima') / 1000;

        $stok1 = InputDataStok::find()->where(["bulan" => $bulan1, "tahun" => $tahun1])->sum('jumlah_stok') / 1000;
        $stok2 = InputDataStok::find()->where(["bulan" => $bulan2, "tahun" => $tahun2])->sum('jumlah_stok') / 1000;
        $stok3 = InputDataStok::find()->where(["bulan" => $bulan3, "tahun" => $tahun3])->sum('jumlah_stok') / 1000;
        $stok4 = InputDataStok::find()->where(["bulan" => $bulan4, "tahun" => $tahun4])->sum('jumlah_stok') / 1000;
        $stok5 = InputDataStok::find()->where(["bulan" => $bulan5, "tahun" => $tahun5])->sum('jumlah_stok') / 1000;
        $stok6 = InputDataStok::find()->where(["bulan" => $bulan6, "tahun" => $tahun6])->sum('jumlah_stok') / 1000;
        $stok7 = InputDataStok::find()->where(["bulan" => $bulan7, "tahun" => $tahun7])->sum('jumlah_stok') / 1000;
        $stok8 = InputDataStok::find()->where(["bulan" => $bulan8, "tahun" => $tahun8])->sum('jumlah_stok') / 1000;
        $stok9 = InputDataStok::find()->where(["bulan" => $bulan9, "tahun" => $tahun9])->sum('jumlah_stok') / 1000;
        $stok10 = InputDataStok::find()->where(["bulan" => $bulan10, "tahun" => $tahun10])->sum('jumlah_stok') / 1000;
        $stok11 = InputDataStok::find()->where(["bulan" => $bulan11, "tahun" => $tahun11])->sum('jumlah_stok') / 1000;
        $stok12 = InputDataStok::find()->where(["bulan" => $bulan12, "tahun" => $tahun12])->sum('jumlah_stok') / 1000;



        return $this->render(
            'awr',
            [
                "dataProvider" => $dataProvider,
                "pembelian1" => $pembelian1,
                "pembelian2" => $pembelian2,
                "pembelian3" => $pembelian3,
                "pembelian4" => $pembelian4,
                "pembelian5" => $pembelian5,
                "pembelian6" => $pembelian6,
                "pembelian7" => $pembelian7,
                "pembelian8" => $pembelian8,
                "pembelian9" => $pembelian9,
                "pembelian10" => $pembelian10,
                "pembelian11" => $pembelian11,
                "pembelian12" => $pembelian12,

                "stok1" => $stok1,
                "stok2" => $stok2,
                "stok3" => $stok3,
                "stok4" => $stok4,
                "stok5" => $stok5,
                "stok6" => $stok6,
                "stok7" => $stok7,
                "stok8" => $stok8,
                "stok9" => $stok9,
                "stok10" => $stok10,
                "stok11" => $stok11,
                "stok12" => $stok12,
            ]
        );
    }

    public function actionUbahPassword()
    {
        $model = \backend\models\User::findOne(\Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validatePassword($model->old_password) == $model->password_hash) {
                $model->setPassword($model->new_password);
                $model->save();
                return $this->render('ubah_password_result', [
                    'hasil' => 'Ubah Password Berhasil.',
                ]);
            } else {
                return $this->render('ubah_password_result', [
                    'hasil' => 'Ubah Password Gagal.',
                ]);
            }
        } else {
            return $this->render('ubah_password', [
                'model' => $model,
            ]);
        }
    }

    public function actionDataPembelian()
    {
        $this->layout = 'main-landing';
        $searchModel = new LandingPembelian();
        $searchModel->wilayah = 3;
        if (!$searchModel->load(Yii::$app->request->get())) {
            $tanggal = new \DateTime();
            $tanggal->modify('-3 month');
            $searchModel->bulan_awal = (int) $tanggal->format('m');
            $searchModel->tahun_awal = (int) $tanggal->format('Y');
            $searchModel->bulan_akhir = (int) $tanggal->format('m');
            $searchModel->tahun_akhir = (int) $tanggal->format('Y');
        }
        //print_R($searchModel->bulan_awal);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data_pembelian', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDataPembelianExport()
    {
        $searchModel = new LandingPembelian();
        $searchModel->wilayah = 3;
        if (!$searchModel->load(Yii::$app->request->get())) {
            $tanggal = new \DateTime();
            $tanggal->modify('-3 month');

            $searchModel->bulan_awal = (int) $tanggal->format('m');
            $searchModel->tahun_awal = (int) $tanggal->format('Y');
            $searchModel->bulan_akhir = (int) $tanggal->format('m');
            $searchModel->tahun_akhir = (int) $tanggal->format('Y');
        }


        $formatter = \Yii::$app->formatter;
        $formatter->locale = 'id-ID';

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $tanggalAwal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
        $tanggalAkhir = new \DateTime($searchModel->tahun_akhir . "-" . $searchModel->bulan_akhir . "-01");

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


        $sheet->setCellValueByColumnAndRow(1, 1, 'Laporan Pembelian Pakan Jagung Nasional');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Total Volume KA 14% (Ton) Dan Rata-Rata Harga KA 14% (Rp/Kg)');
        $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y') . ' Sampai Dengan ' . $formatter->asDateTime($tanggalAkhir, 'php:F') . " " . $tanggalAkhir->format('Y');
        if ($searchModel->bulan_awal == $searchModel->bulan_akhir && $searchModel->tahun_awal == $searchModel->tahun_akhir) {
            $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y');
        }
        $sheet->setCellValueByColumnAndRow(1, 3, $periode);
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');

        // create header

        $row = 5;
        $sheet->setCellValue('A' . $row, 'No.');
        $sheet->mergeCells('A' . $row . ':A' . $row + 2);
        $sheet->setCellValue('B' . $row, 'Nama');
        $sheet->mergeCells('B' . $row . ':B' . $row + 2);
        $row++;
        $col = 3;
        $interval = $tanggalAwal->diff($tanggalAkhir);
        $intervalBulan = $interval->m + ($interval->y * 12);
        $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
        $tahunSekarang = 0;
        for ($i = 0; $i <= $intervalBulan; $i++) {
            $tahun = $formatter->asDateTime($tanggal, 'php:Y');
            if ($tahun != $tahunSekarang) {
                $rowTahun = $row - 1;
                $tahunSekarang = $tahun;
                $sheet->setCellValueByColumnAndRow($col, $rowTahun, $tahunSekarang);
                $colSpan = 12 * 2;
                if ($tahun == $searchModel->tahun_awal) {
                    $colSpan = (13 - $searchModel->bulan_awal) * 2;
                }
                if ($tahun == $searchModel->tahun_akhir) {
                    $colSpan = $searchModel->bulan_akhir * 2;
                }
                $colMerge = $col + $colSpan - 1;
                $curCol = Coordinate::stringFromColumnIndex($col);
                $mergeCol = Coordinate::stringFromColumnIndex($colMerge);
                $sheet->mergeCells($curCol . $rowTahun . ':' . $mergeCol . $rowTahun);
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $formatter->asDateTime($tanggal, 'php:F'));
            $curCol = Coordinate::stringFromColumnIndex($col); // ZERO-based!
            $nextCol = Coordinate::stringFromColumnIndex($col + 1); // ZERO-based!
            $spreadsheet->getActiveSheet()->getStyle($curCol)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $spreadsheet->getActiveSheet()->getStyle($curCol . '' . $rowTahun)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle($nextCol)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->mergeCells($curCol . $row . ':' . $nextCol . $row);
            $sheet->setCellValueByColumnAndRow($col, $row + 1, 'Volume');
            $sheet->setCellValueByColumnAndRow($col + 1, $row + 1, 'Harga');
            $tanggal->modify('+1 month');
            $col += 2;
        }
        // tolong dilanjutkan ya

        $row += 2;
        $firstRow = $row;

        foreach ($dataProvider as $id_pulau => $pulau) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col, $row, $pulau['nama']);
            $col = 3;
            $interval = $tanggalAwal->diff($tanggalAkhir);
            $intervalBulan = $interval->m + ($interval->y * 12);
            $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
            for ($i = 0; $i <= $intervalBulan; $i++):
                //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                $tahun = $tanggal->format('Y');
                $bulan = (int) $tanggal->format('m');
                $sheet->setCellValueByColumnAndRow($col, $row, $pulau['tahun'][$tahun]['bulan'][$bulan]['volume']);
                $sheet->setCellValueByColumnAndRow($col + 1, $row, $pulau['tahun'][$tahun]['bulan'][$bulan]['harga']);
                $summary[$tahun]['bulan'][$bulan]['volume'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['volume'];
                $summary[$tahun]['bulan'][$bulan]['harga'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['harga_semua'];
                $summary[$tahun]['bulan'][$bulan]['count'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['count'];
                $col += 2;
                $tanggal->modify('+1 month');
            endfor;
            $row++;

            $no = 1;
            foreach ($pulau['provinsi'] as $id_provinsi => $provinsi) {
                $col = 1;
                $sheet->setCellValueByColumnAndRow($col, $row, $no);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row, $provinsi['nama']);
                $col = 3;
                $interval = $tanggalAwal->diff($tanggalAkhir);
                $intervalBulan = $interval->m + ($interval->y * 12);
                $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                for ($i = 0; $i <= $intervalBulan; $i++):
                    //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                    $tahun = $tanggal->format('Y');
                    $bulan = (int) $tanggal->format('m');
                    $sheet->setCellValueByColumnAndRow($col, $row, $provinsi['tahun'][$tahun]['bulan'][$bulan]['volume']);
                    $sheet->setCellValueByColumnAndRow($col + 1, $row, $provinsi['tahun'][$tahun]['bulan'][$bulan]['harga']);
                    //$summary[$tahun][$bulan]['volume'] += $provinsi['tahun'][$tahun][$bulan]['volume'];
                    //$summary[$tahun][$bulan]['harga'] += $provinsi['tahun'][$tahun][$bulan]['harga_semua'];
                    //$summary[$tahun][$bulan]['count'] += $provinsi['tahun'][$tahun][$bulan]['count'];
                    $col += 2;
                    $tanggal->modify('+1 month');
                endfor;
                $no++;
                $row++;
            }

            
        }

        $lastRow = $row - 1;
        $col = 3;
        $sheet->setCellValueByColumnAndRow(2, $row, '');
        foreach ($summary as $tahun => $itemTahun) {
            foreach ($itemTahun['bulan'] as $bulan => $itemBulan) {
                $sheet->setCellValueByColumnAndRow($col, $row, $itemBulan['volume']);
                $sheet->setCellValueByColumnAndRow($col + 1, $row, $itemBulan['harga'] / $itemBulan['count']);
                $col += 2;
            }
        }
        // style
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle(
            'A5:' .
            $highestColumn .
            $highestRow
        )->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '7')->getAlignment()
            ->SetVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Set Vertical Centralization
            ->SetHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Settings are in the middle
            ->setWrapText(true); // Set automatic line break
        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '7')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':' . $highestColumn . $row)->getFont()->setBold(true);

        foreach (range('B', $highestColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_data_pembelian.xlsx"');
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

    public function actionDataStok()
    {
        $this->layout = 'main-landing';
        $searchModel = new LandingStok();
        $searchModel->wilayah = 3;
        if (!$searchModel->load(Yii::$app->request->get())) {
            $tanggal = new \DateTime();
            $tanggal->modify('-3 month');
            $searchModel->bulan_awal = (int) $tanggal->format('m');
            $searchModel->tahun_awal = (int) $tanggal->format('Y');
            $searchModel->bulan_akhir = (int) $tanggal->format('m');
            $searchModel->tahun_akhir = (int) $tanggal->format('Y');
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data_stok', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDataStokExport()
    {
        $searchModel = new LandingStok();
        $searchModel->wilayah = 3;
        if (!$searchModel->load(Yii::$app->request->get())) {
            $tanggal = new \DateTime();
            $tanggal->modify('-3 month');

            $searchModel->bulan_awal = date('m');
            $searchModel->tahun_awal = date('Y');
            $searchModel->bulan_akhir = date('m');
            $searchModel->tahun_akhir = date('Y');
        }

        $formatter = \Yii::$app->formatter;
        $formatter->locale = 'id-ID';


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $tanggalAwal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
        $tanggalAkhir = new \DateTime($searchModel->tahun_akhir . "-" . $searchModel->bulan_akhir . "-01");


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


        $sheet->setCellValueByColumnAndRow(1, 1, 'Laporan Stok Pakan Jagung Nasional');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Total Stok (Ton) Dan Rata-rata Kecukupan Produksi (Hari)');
        $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y') . ' Sampai Dengan ' . $formatter->asDateTime($tanggalAkhir, 'php:F') . " " . $tanggalAkhir->format('Y');
        if ($searchModel->bulan_awal == $searchModel->bulan_akhir && $searchModel->tahun_awal == $searchModel->tahun_akhir) {
            $periode = $formatter->asDateTime($tanggalAwal, 'php:F') . " " . $tanggalAwal->format('Y');
        }
        $sheet->setCellValueByColumnAndRow(1, 3, $periode);
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');

        // create header

        $row = 5;
        $sheet->setCellValue('A' . $row, 'No.');
        $sheet->mergeCells('A' . $row . ':A' . $row + 2);
        $sheet->setCellValue('B' . $row, 'Nama');
        $sheet->mergeCells('B' . $row . ':B' . $row + 2);
        $row++;
        $col = 3;
        $interval = $tanggalAwal->diff($tanggalAkhir);
        $intervalBulan = $interval->m + ($interval->y * 12);
        $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
        $tahunSekarang = 0;
        for ($i = 0; $i <= $intervalBulan; $i++) {
            $tahun = $formatter->asDateTime($tanggal, 'php:Y');
            if ($tahun != $tahunSekarang) {
                $rowTahun = $row - 1;
                $tahunSekarang = $tahun;
                $sheet->setCellValueByColumnAndRow($col, $rowTahun, $tahunSekarang);
                $colSpan = 12 * 2;
                if ($tahun == $searchModel->tahun_awal) {
                    $colSpan = (13 - $searchModel->bulan_awal) * 2;
                }
                if ($tahun == $searchModel->tahun_akhir) {
                    $colSpan = $searchModel->bulan_akhir * 2;
                }
                $colMerge = $col + $colSpan - 1;
                $curCol = Coordinate::stringFromColumnIndex($col);
                $mergeCol = Coordinate::stringFromColumnIndex($colMerge);
                $sheet->mergeCells($curCol . $rowTahun . ':' . $mergeCol . $rowTahun);
            }
            $sheet->setCellValueByColumnAndRow($col, $row, $formatter->asDateTime($tanggal, 'php:F'));
            $curCol = Coordinate::stringFromColumnIndex($col); // ZERO-based!
            $nextCol = Coordinate::stringFromColumnIndex($col + 1); // ZERO-based!
            $spreadsheet->getActiveSheet()->getStyle($curCol)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $spreadsheet->getActiveSheet()->getStyle($curCol . '' . $rowTahun)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle($nextCol)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->mergeCells($curCol . $row . ':' . $nextCol . $row);
            $sheet->setCellValueByColumnAndRow($col, $row + 1, 'Stok');
            $sheet->setCellValueByColumnAndRow($col + 1, $row + 1, 'Kecukupan');
            $tanggal->modify('+1 month');
            $col += 2;
        }
        // tolong dilanjutkan ya

        $row += 2;
        $firstRow = $row;
        $no = 1;
        foreach ($dataProvider as $id_pulau => $pulau) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col, $row, $pulau['nama']);
            $col = 3;
            $interval = $tanggalAwal->diff($tanggalAkhir);
            $intervalBulan = $interval->m + ($interval->y * 12);
            $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
            for ($i = 0; $i <= $intervalBulan; $i++):
                //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                $tahun = $tanggal->format('Y');
                $bulan = (int) $tanggal->format('m');
                $sheet->setCellValueByColumnAndRow($col, $row, $pulau['tahun'][$tahun]['bulan'][$bulan]['stok']);
                $sheet->setCellValueByColumnAndRow($col + 1, $row, $pulau['tahun'][$tahun]['bulan'][$bulan]['kecukupan']);
                $summary[$tahun]['bulan'][$bulan]['stok'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['stok'];
                $summary[$tahun]['bulan'][$bulan]['kecukupan'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['kecukupan_semua'];
                $summary[$tahun]['bulan'][$bulan]['count'] += $pulau['tahun'][$tahun]['bulan'][$bulan]['count'];
                $col += 2;
                $tanggal->modify('+1 month');
            endfor;
            $row++;

            $no = 1;
            foreach ($pulau['provinsi'] as $id_provinsi => $provinsi) {
                $col = 1;
                $sheet->setCellValueByColumnAndRow($col, $row, $no);
                $col++;
                $sheet->setCellValueByColumnAndRow($col, $row, $provinsi['nama']);
                $col = 3;
                $interval = $tanggalAwal->diff($tanggalAkhir);
                $intervalBulan = $interval->m + ($interval->y * 12);
                $tanggal = new \DateTime($searchModel->tahun_awal . "-" . $searchModel->bulan_awal . "-01");
                for ($i = 0; $i <= $intervalBulan; $i++):
                    //echo (int)$tanggal->format('m').' '.$tanggal->format('Y'). ' = '.$item['tahun'][$tanggal->format('Y')][(int)$tanggal->format('m')]['volume'].' - ';
                    $tahun = $tanggal->format('Y');
                    $bulan = (int) $tanggal->format('m');
                    $sheet->setCellValueByColumnAndRow($col, $row, $provinsi['tahun'][$tahun]['bulan'][$bulan]['stok']);
                    $sheet->setCellValueByColumnAndRow($col + 1, $row, $provinsi['tahun'][$tahun]['bulan'][$bulan]['kecukupan']);
                    //$summary[$tahun][$bulan]['volume'] += $provinsi['tahun'][$tahun][$bulan]['volume'];
                    //$summary[$tahun][$bulan]['harga'] += $provinsi['tahun'][$tahun][$bulan]['harga_semua'];
                    //$summary[$tahun][$bulan]['count'] += $provinsi['tahun'][$tahun][$bulan]['count'];
                    $col += 2;
                    $tanggal->modify('+1 month');
                endfor;
                $no++;
                $row++;
            }

            
        }

        $lastRow = $row - 1;
        $col = 3;
        $sheet->setCellValueByColumnAndRow(2, $row, '');
        foreach ($summary as $tahun => $itemTahun) {
            foreach ($itemTahun['bulan'] as $bulan => $itemBulan) {
                $sheet->setCellValueByColumnAndRow($col, $row, $itemBulan['stok']);
                $sheet->setCellValueByColumnAndRow($col + 1, $row, $itemBulan['kecukupan'] / $itemBulan['count']);
                $col += 2;
            }
        }

        // style
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle(
            'A5:' .
            $highestColumn .
            $highestRow
        )->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '7')->getAlignment()
            ->SetVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Set Vertical Centralization
            ->SetHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Settings are in the middle
            ->setWrapText(true); // Set automatic line break
        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '7')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':' . $highestColumn . $row)->getFont()->setBold(true);
        //$spreadsheet->getActiveSheet()->getStyle('C'.$firstRow.':'.$highestColumn.$row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        foreach (range('B', $highestColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_data_stok.xlsx"');
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

    public function actionDataProyeksi()
    {
        $this->layout = 'main-landing';
        $searchModel = new LandingProyeksi();
        $searchModel->wilayah = 3;
        if (!$searchModel->load(Yii::$app->request->get())) {
            $searchModel->tahun = date('Y') + 1;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data_proyeksi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDataProyeksiExport()
    {
        $searchModel = new LandingProyeksi();
        $searchModel->wilayah = 3;
        $formatter = \Yii::$app->formatter;
        $formatter->locale = 'id-ID';
        if (!$searchModel->load(Yii::$app->request->get())) {
            $searchModel->tahun = date('Y') + 1;
        }
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

        $sheet->setCellValueByColumnAndRow(1, 1, 'Laporan Proyeksi Pakan Jagung Nasional');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Kadar Air 14% (Ton)');
        $sheet->setCellValueByColumnAndRow(1, 3, 'Tahun ' . $searchModel->tahun);
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');

        // create header
        $row = 5;
        $rowPulau = [];
        $sheet->setCellValue('A' . $row, 'No.');
        if ($searchModel->wilayah == 1) {
            $sheet->setCellValue('B' . $row, 'Nama Pulau');
        } elseif ($searchModel->wilayah == 2) {
            $sheet->setCellValue('B' . $row, 'Nama Provinsi');
        } else {
            $sheet->setCellValue('B' . $row, 'Nama Plant');
        }
        $col = 3;
        for ($i = 1; $i <= 12; $i++) {
            $tanggal = new \DateTime($searchModel->tahun . "-" . $i . "-01");
            $monthName = $formatter->asDateTime($tanggal, 'php:F'); // March
            $sheet->setCellValueByColumnAndRow($col, $row, $monthName);
            $col++;
        }
        $sheet->setCellValueByColumnAndRow($col, $row, 'Total');
        $row++;
        $firstRow = $row;
        foreach ($dataProvider as $pulau) {
            $rowPulau[] = $row;
            $sheet->setCellValue('A' . $row, $pulau['nama']);
            $col = 3;
            $startCol = 3;
            for ($i = 1; $i <= 12; $i++) {
                $sheet->setCellValueByColumnAndRow($col, $row, $pulau['bulan'][$i]['volume']);
                $col++;
            }
            $firstCol = Coordinate::stringFromColumnIndex($startCol);
            $lastCol = Coordinate::stringFromColumnIndex($col - 1);
            $sheet->setCellValueByColumnAndRow($col, $row, '=SUM(' . $firstCol . $row . ':' . $lastCol . $row . ')');
            $row++;

            $noProvinsi = 1;
            foreach ($pulau['provinsi'] as $provinsi) {
                $sheet->setCellValue('A' . $row, $noProvinsi);
                $sheet->setCellValue('B' . $row, $provinsi['nama']);
                $col = 3;
                $startCol = 3;
                for ($i = 1; $i <= 12; $i++) {
                    $sheet->setCellValueByColumnAndRow($col, $row, $provinsi['bulan'][$i]['volume']);
                    $col++;
                }
                $firstCol = Coordinate::stringFromColumnIndex($startCol);
                $lastCol = Coordinate::stringFromColumnIndex($col - 1);
                $sheet->setCellValueByColumnAndRow($col, $row, '=SUM(' . $firstCol . $row . ':' . $lastCol . $row . ')');

                $row++;
                $noProvinsi++;
            }
            $row++;
        }

        $lastRow = $row - 1;
        $col = 3;
        $sheet->setCellValueByColumnAndRow(2, $row, 'Jumlah');

        for ($i = 1; $i <= 13; $i++) {
            $sumRow = '';
            $curCol = Coordinate::stringFromColumnIndex($col); // ZERO-based!
            foreach ($rowPulau as $baris) {
                $sumRow .= $curCol . '' . $baris . '+';
            }
            $sumRow = rtrim($sumRow, '+');
            $sheet->setCellValueByColumnAndRow($col, $row, '=' . $sumRow);
            $col++;
        }

        // style
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle(
            'A5:' .
            $highestColumn .
            $highestRow
        )->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '5')->getAlignment()
            ->SetVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Set Vertical Centralization
            ->SetHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER) /// Settings are in the middle
            ->setWrapText(true); // Set automatic line break
        $spreadsheet->getActiveSheet()->getStyle('A1:' . $highestColumn . '5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A' . $row . ':' . $highestColumn . $row)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('C' . $firstRow . ':' . $highestColumn . $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        foreach (range('B', $highestColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
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