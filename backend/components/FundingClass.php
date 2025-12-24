<?php

namespace yii\components;

use yii\base\Object;
use common\models\User;
class FundingClass extends Object
{
	
	private $user;
	private $token;
	private $username;
	private $password;
	private $imei;
	private $version;
	public $connection;
	private $dbHost = '';
	private $dbUser = '';
	private $dbPassword = '';
	private $dbCatalog = '';

    public function init()
    {
        parent::init();
    }

	public function __construct($config = [])
    {
        parent::__construct($config);
    }
	
	public function validate($username, $password, $imei, $version, $token)
	{		
		// validasi versi aplikasi
		if($version != $this->appVersion){
			$this->response['error'][]='Anda menggunakan aplikasi versi '.$version.', download update versi '.$this->appVersion.' di playstore.';
			return false; 
		}
		
		//validasi user
		$user = User::findIdentityByAccessToken($token);
		if($user === null){
			$this->response['error'][]='User tidak ditemukan.';
			return false; 
		}else{
			$this->user = $user;
		}
		
		// validasi password
		if(!$user->validatePassword($password)){
			$this->response['error'][]='Password tidak sesuai.';
			return false; 
		} 
		
		// validasi nomor imei
		if($user->imei!=$imei){
			$this->response['error'][]='Nomor IMEI tidak sesuai.';
			return false; 
		}
		
		return true;
	}
	
	public function getConnection()
	{
		$host = $this->user->petugas->bmt->host;
		$username = $this->user->petugas->bmt->username;
		$password = $this->user->petugas->bmt->password;
		$database = $this->user->petugas->bmt->database;
		
		$this->connection = new \yii\db\Connection([
			'dsn' => 'dblib:host='.$host.';dbname='.$database,
			'username' => $username,
			'password' => $password,
			'charset' => 'utf8',
		]);
		
		try {
			$this->connection->open();
			return true;
		} catch (\yii\db\Exception $e) {
			$this->response['error'] = "Database cannot open";
			$this->response['database'] = $e->errorInfo;
			return false;
		}
	}
	
	public function getUser(){
		return $this->user;
	}
	
}