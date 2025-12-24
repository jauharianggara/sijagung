<?php

namespace backend\modules\v1\controllers;

use Yii;
use common\models\User;
class RestController extends \yii\rest\Controller
{
	public $appVersion;
	public $appVersionDebug;
	public $connection;
	public $response;
	public $user;
	public $imei;
	public $log;

    public function behaviors()
    {
        return [
			[
				'class' => \yii\filters\ContentNegotiator::className(),
				'formats' => [
					'application/json' => \yii\web\Response::FORMAT_JSON,
					'charset' => 'UTF-8',
				],
			],
			[
                'class' => \yii\filters\auth\CompositeAuth::className(),
                'authMethods' => [
                    \yii\filters\auth\HttpBasicAuth::className(),
                    \yii\filters\auth\HttpBearerAuth::className(),
                    \yii\filters\auth\QueryParamAuth::className(),
                ],
            ],
        ];
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

	private function validate($username,$password,$version,$imei)
	{
		// validasi versi aplikasi
		$this->appVersion = \Yii::$app->params['version'];
		$this->appVersionDebug = \Yii::$app->params['versionDebug'];
		if($version != $this->appVersion && $version != $this->appVersionDebug){
			throw new \yii\web\HttpException(401, 'Anda menggunakan aplikasi versi '.$version.', download update versi '.$this->appVersion.' di playstore.');
		}

		//validasi user
		$user = User::findByUsername($username);
		if($user === null){
			throw new \yii\web\HttpException(401, 'User tidak ditemukan.');
		}else{
			$this->user = $user;
		}

		// validasi nomor imei
		if($user->petugas->imei!=$imei){
			throw new \yii\web\HttpException(401, 'Nomor IMEI tidak sesuai.');
		}
	}

	public function beforeAction($action)
	{
		$request = Yii::$app->request;
		$username = $request->post('username');
		$password = $request->post('password');
		$imei = $request->post('imei');
		$version = $request->post('version');

		$this->imei = $imei;
		if(parent::beforeAction($action)) {
			$this->validate($username,$password,$version,$imei);
			return true;
		}
		return false;
	}

	public function afterAction($action, $result)
	{
		$request = Yii::$app->request;
		/**
		$userLog = new UserLog();
		$userLog->tanggal = date("Y-m-d H:i:s");
		$userLog->userId = $this->user->id;
		$userLog->url = $request->url;

		$param = '';
		foreach($request->post() as $key => $value) {
			$param  .= $key.' = '.$value.',';
		}

		$userLog->parameter = $param;
		//$userLog->keterangan = '';
		$userLog->save();
		**/

		//print_r($result);
		//$hasil =  utf8_encode($result);
		//print_r($hasil);
		$result = parent::afterAction($action, $result);
		return $result;
	}

	public static function array_utf8_encode($dat)
	{
		if (is_string($dat))
			return utf8_encode($dat);
		if (!is_array($dat))
			return $dat;
		$ret = array();
		foreach ($dat as $i => $d)
			$ret[$i] = self::array_utf8_encode($d);
		return $ret;
	}
}
