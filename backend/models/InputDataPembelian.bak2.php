<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "input_data_pembelian".
 *
 * @property int $id
 * @property string $bulan
 * @property int $kapo
 * @property int $harga_po
 * @property int $harga_ka
 * @property int $quantity_terima
 * @property string $harga_terima
 * @property string $ka_terima
 * @property string $asal_jagung
 * @property string $pemasok
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class InputDataPembelian extends \yii\db\ActiveRecord
{
	private $_plant;
	public $sumber;
	public $supplier;
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
        return ['inputDataPembelianSumber','provinsiLabel', 'plant'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'input_data_pembelian';
    }

	public function scenarios()
  {
      $scenarios = parent::scenarios();
      $scenarios[self::SCENARIO_CREATE] = ['bulan', 'kapo', 'quantity_terima','tahun', 'harga_po', 'harga_terima', 'ka_terima',  'sumber','agreement','supplier'];
      $scenarios[self::SCENARIO_UPDATE] = ['bulan', 'kapo', 'quantity_terima','tahun', 'harga_po', 'harga_terima', 'ka_terima', 'sumber','supplier'];
      return $scenarios;
  }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
					[['bulan', 'kapo', 'quantity_terima','tahun', 'harga_po', 'harga_ka', 'harga_terima', 'ka_terima', 'pemasok'], 'required'],
					[['userAdd', 'userEdit','tahun','id_plant', 'harga_po', 'harga_ka'], 'integer'],
					[['dateAdd', 'dateEdit','sumber','supplier'], 'safe'],
					[['ka_terima', 'quantity_terima', 'kapo'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
					['agreement', 'boolean'],
					[['bulan', 'harga_terima', 'asal_jagung', 'pemasok'], 'string', 'max' => 255],
					[['bulan', 'kapo', 'quantity_terima','tahun', 'harga_po', 'harga_ka', 'harga_terima', 'ka_terima', 'pemasok','agreement', 'sumber','supplier'], 'required', 'on' => self::SCENARIO_CREATE],
					[['bulan', 'kapo', 'quantity_terima','tahun', 'harga_po', 'harga_ka', 'harga_terima', 'ka_terima', 'pemasok', 'sumber','supplier'], 'required', 'on' => self::SCENARIO_UPDATE],
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
            'kapo' => 'KA PO',
            'harga_po' => 'Harga PO',
            'harga_ka' => 'Harga KA',
            'quantity_terima' => 'Quantity Terima',
            'harga_terima' => 'Harga Terima',
            'ka_terima' => 'KA Terima',
            'asal_jagung' => 'Asal Jagung',
            'pemasok' => 'Pemasok',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
						'agreement'=>'Setuju',
        ];
    }
    public function getPlant()
    {
        return $this->hasOne(ProfilPlant::className(), ['id_user' => 'id_plant']);
    }

	  public function getProvinsiLabel()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'asal_jagung']);
    }

		public function getInputDataPembelianSumber()
    {
        return $this->hasMany(InputDataPembelianSumber::className(), ['id_input_data_pembelian' => 'id']);
    }

		public function getInputDataPembelianSuppliers()
    {
        return $this->hasMany(InputDataPembelianSupplier::className(), ['id_input_data_pembelian' => 'id']);
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

		public function getKonversiVolume(){
			$kadarStandart = 15;
			$kadarKonversi = $this->quantity_terima;
			//echo 'KA Terima :'.$this->ka_terima.' -- ';
			if($this->ka_terima > $kadarStandart){

				for($i = $this->ka_terima; $i > $kadarStandart ; $i--){
					$kadarBefore = $kadarKonversi;
					$kadarKonversi = $kadarKonversi - ($kadarKonversi * 0.015);
					//echo $i.' ';
					//echo $kadarKonversi.'  -- ';
				}
				$num = $i;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					//echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
					$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
				}
				// koma
			}else if($this->ka_terima < $kadarStandart){
				$num = $this->ka_terima;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					$kadarStandart++;
					for($i = $this->ka_terima; $i <= $kadarStandart ; $i++){
						$kadarBefore = $kadarKonversi;
						$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015);
						//echo $kadarKonversi.'  -- ';
					}
				}else{
					for($i = $this->ka_terima; $i < $kadarStandart ; $i++){
						$kadarBefore = $kadarKonversi;
						$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015);
						//echo $kadarKonversi.'  -- ';
					}
				}


				//echo '<br>';
				$num = $i;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					//echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
					$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
				}
			}
			return $kadarKonversi;
		}

		public function getKonversiVolume14(){
			$kadarStandart = 14;
			$kadarKonversi = $this->quantity_terima;
			//echo 'KA Terima :'.$this->ka_terima.' -- ';
			if($this->ka_terima > $kadarStandart){

				for($i = $this->ka_terima; $i > $kadarStandart ; $i--){
					$kadarBefore = $kadarKonversi;
					$kadarKonversi = $kadarKonversi - ($kadarKonversi * 0.015);
					//echo $i.' ';
					//echo $kadarKonversi.'  -- ';
				}
				$num = $i;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					//echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
					$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
				}
				echo "$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015) | ";


				// koma
			}else if($this->ka_terima < $kadarStandart){
				$num = $this->ka_terima;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					$kadarStandart++;
					for($i = $this->ka_terima; $i <= $kadarStandart ; $i++){
						$kadarBefore = $kadarKonversi;
						$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015);
						//echo $kadarKonversi.'  -- ';
					}
					echo "$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015) | ";

				}else{
					for($i = $this->ka_terima; $i < $kadarStandart ; $i++){
						$kadarBefore = $kadarKonversi;
						$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015);
						//echo $kadarKonversi.'  -- ';
					}
					echo "$kadarKonversi = $kadarKonversi + ($kadarKonversi  * 0.015) | ";

				}


				//echo '<br>';
				$num = $i;
				$whole = (int) $num;  // 5
				$frac  = $num - $whole;  // .7
				//echo $frac.'<br>';
				if($frac > 0 ){
					//echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
					$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
				}
				echo "$kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015) | ";
			}
			return $kadarKonversi;
		}

		public function getKonversiHarga(){
			$hargaKonversi  = ($this->harga_terima * $this->quantity_terima)/$this->konversiVolume;
			return $hargaKonversi;
		}

		public function getKonversiHarga14(){
			$hargaKonversi  = ($this->harga_terima * $this->quantity_terima)/$this->konversiVolume14;
			return $hargaKonversi;
		}


}
