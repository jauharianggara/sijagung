<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\InputDataPembelian;
use backend\models\InputDataStok;
use yii\data\SqlDataProvider;
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        if(\Yii::$app->authManager-> getAssignment('admin',\Yii::$app->user->id)){
          $bulan1 =  date('m', strtotime('-2 months'));
          $bulan2 = date('m', strtotime('-1 months'));
          $bulan3 =  date('m');

          $tahun1 = date('Y', strtotime('-2 months'));
          $tahun2 = date('Y', strtotime('-1 months'));
          $tahun3 = date('Y');

          $bulanLabel1 = date('F', strtotime('-2 months'));
          $bulanLabel2 = date('F', strtotime('-1 months'));
          $bulanLabel3 = date('F');

          $sql = "SELECT COUNT(profil_plant.id) from profil_plant INNER JOIN user ON profil_plant.id_user=user.id;";
          $count = Yii::$app->db->createCommand($sql)->queryScalar();

          $sql = "SELECT
          profil_plant.id,
          profil_plant.nama_plant,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan1."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun1."') AS pembelian1,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan2."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun2."') AS pembelian2,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan3."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun3."') AS pembelian3,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan1."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun1."') AS stok1,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan2."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun2."') AS stok2,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan3."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun3."') AS stok3
          from profil_plant INNER JOIN user ON profil_plant.id_user=user.id
          ORDER BY nama_plant";

          $dataProvider = new SqlDataProvider([
              'sql' => $sql,
              'totalCount' => $count,
              'pagination' => false,
          ]);

          $pembelian1 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan1,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun1])->sum('quantity_terima');
          $pembelian2 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan2,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun2])->sum('quantity_terima');
          $pembelian3 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan3,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun3])->sum('quantity_terima');

          $stok1 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan1,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun1])->sum('jumlah_stok');
          $stok2 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan2,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun2])->sum('jumlah_stok');
          $stok2 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan3,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun3])->sum('jumlah_stok');


          return $this->render(
            'index',
            [
              "dataProvider"=>$dataProvider,
              "pembelian1"=>$pembelian1,
              "pembelian2"=>$pembelian2,
              "pembelian3"=>$pembelian3,
              "stok1"=>$stok1,
              "stok2"=>$stok2,
              "stok3"=>$stok3,
            ]
          );
        }else{
          $bulan1 =  date('m', strtotime('-2 months'));
          $bulan2 = date('m', strtotime('-1 months'));
          $bulan3 =  date('m');

          $tahun1 = date('Y', strtotime('-2 months'));
          $tahun2 = date('Y', strtotime('-1 months'));
          $tahun3 = date('Y');

          $bulanLabel1 = date('F', strtotime('-2 months'));
          $bulanLabel2 = date('F', strtotime('-1 months'));
          $bulanLabel3 = date('F');

          $sql = "SELECT COUNT(profil_plant.id) from profil_plant INNER JOIN user ON profil_plant.id_user=user.id;";
          $count = Yii::$app->db->createCommand($sql)->queryScalar();

          $sql = "SELECT
          profil_plant.id,
          profil_plant.nama_plant,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan1."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun1."') AS pembelian1,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan2."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun2."') AS pembelian2,
          (SELECT COUNT(input_data_pembelian.id) FROM input_data_pembelian WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan3."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun3."') AS pembelian3,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan1."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun1."') AS stok1,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan2."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun2."') AS stok2,
          (SELECT COUNT(input_data_stok.id) FROM input_data_stok WHERE 	id_plant=user.id AND DATE_FORMAT(dateAdd, '%m')='".$bulan3."' AND DATE_FORMAT(dateAdd, '%Y')='".$tahun3."') AS stok3
          from profil_plant INNER JOIN user ON profil_plant.id_user=user.id
          ORDER BY nama_plant";

          $dataProvider = new SqlDataProvider([
              'sql' => $sql,
              'totalCount' => $count,
              'pagination' => false,
          ]);

          $pembelian1 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan1,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun1,"id_plant"=>\Yii::$app->user->id])->sum('quantity_terima');
          $pembelian2 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan2,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun2,"id_plant"=>\Yii::$app->user->id])->sum('quantity_terima');
          $pembelian3 = InputDataPembelian::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan3,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun3,"id_plant"=>\Yii::$app->user->id])->sum('quantity_terima');

          $stok1 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan1,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun1,"id_plant"=>\Yii::$app->user->id])->sum('jumlah_stok');
          $stok2 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan2,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun2,"id_plant"=>\Yii::$app->user->id])->sum('jumlah_stok');
          $stok2 = InputDataStok::find()->where(["DATE_FORMAT(dateAdd, '%m')"=>$bulan3,"DATE_FORMAT(dateAdd, '%Y')"=>$tahun3,"id_plant"=>\Yii::$app->user->id])->sum('jumlah_stok');


          return $this->render(
            'index',
            [
              "dataProvider"=>$dataProvider,
              "pembelian1"=>$pembelian1,
              "pembelian2"=>$pembelian2,
              "pembelian3"=>$pembelian3,
              "stok1"=>$stok1,
              "stok2"=>$stok2,
              "stok3"=>$stok3,
            ]
          );
        }

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
            return $this->render('login', [
                'model' => $model,
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
}
