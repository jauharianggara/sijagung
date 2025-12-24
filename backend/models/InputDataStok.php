<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use hscstudio\mimin\components\Mimin;
use hscstudio\mimin\models\AuthAssignment;
/**
 * This is the model class for table "input_data_stok".
 *
 * @property int $id
 * @property string $bulan
 * @property int $jumlah_stok
 * @property int $kecukupan_produksi
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class InputDataStok extends \yii\db\ActiveRecord
{
	public $agreement;

	const SCENARIO_CREATE = 'create';
	const SCENARIO_UPDATE = 'update';

  public function behaviors()
  {
      return [
        [
          'class' => BlameableBehavior::className(),
          'createdByAttribute' => 'userAdd',
          'updatedByAttribute' => 'userEdit',
        ],
        [
          'class' => TimestampBehavior::className(),
          'createdAtAttribute' => 'dateAdd',
          'updatedAtAttribute' => 'dateEdit',
          'value' => new Expression('NOW()'),
        ],
      ];
  }

    public function fields()
    {
      $fields = parent::fields();
      $fields['bulanLabel'] = 'bulanLabel';
      return $fields;
    }

    public function extraFields() {
        return ['plant'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'input_data_stok';
    }

	  public function scenarios()
  {
      $scenarios = parent::scenarios();
      $scenarios[self::SCENARIO_CREATE] = ['bulan', 'jumlah_stok','tahun', 'bulan', 'kecukupan_produksi','agreement'];
      $scenarios[self::SCENARIO_UPDATE] = ['bulan', 'jumlah_stok','tahun', 'bulan', 'kecukupan_produksi'];
      return $scenarios;
  }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulan', 'jumlah_stok','tahun', 'bulan', 'kecukupan_produksi'], 'required'],
            [['userAdd', 'userEdit','tahun','bulan','id_plant', 'kecukupan_produksi'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
						[['jumlah_stok'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
						['agreement', 'required', 'on' => ['register'], 'message' => 'my test message'],
						[['bulan', 'jumlah_stok','tahun', 'bulan', 'kecukupan_produksi','agreement'], 'required', 'on' => self::SCENARIO_CREATE],
						['agreement', 'required', 'on' => self::SCENARIO_CREATE, 'requiredValue' => 1, 'message' => 'Harap checklist persetujuan di atas.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bulan' => 'Bulan',
            'jumlah_stok' => 'Jumlah Stok (Kg)',
            'kecukupan_produksi' => 'Kecukupan Produksi (Hari)',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
            'agreement'=>'Setuju',
        ];
    }

		public function afterSave($isNew, $old) {
       if ($isNew){

       }else{
				 if(\Yii::$app->authManager->getAssignment('plant',\Yii::$app->user->id)){
					 $notif = new Notif;
					 $notif->id_notif_type = 2;
					 $notif->judul = "Update Data Stok";
					 $notif->keterangan = \Yii::$app->user->identity->nama." telah mengupdate Data Stok ".$this->bulanLabel.' '.$this->tahun;
					 $notif->id_object = $this->id;
					 $notif->id_user = \Yii::$app->user->id;
					 $notif->tanggal = date('Y-m-d H:i:s');
					 if($notif->save()){
						 $users = AuthAssignment::find()->where(['item_name'=>'admin'])->all();
						 if($users){
							 foreach ($users as $user) {
								 $notifUser = new NotifUser;
								 $notifUser->id_notif = $notif->id;
								 $notifUser->id_user = $user->user_id;
								 $notifUser->dateSend = $notif->tanggal;
								 $notifUser->status = 0;
								 $notifUser->save();
							 }
						 }
					 }
				 }
			 }
   }

    public function getPlant()
    {
        return $this->hasOne(ProfilPlant::className(), ['id_user' => 'id_plant']);
    }

		public function getBulanLabel()
    {
      $label = array (
			"0"=>"",
			"1"=>"Januari",
			"2"=>"Februari",
			"3"=>"Maret",
			"4"=>"April",
			"5"=>"Mei",
			"6"=>"Juni",
			"7"=>"Juli",
			"8"=>"Agustus",
			"9"=>"September",
			"10"=>"Oktober",
			"11"=>"November",
			"12"=>"Desember",
			);
			return $label[$this->bulan];
    }
}
