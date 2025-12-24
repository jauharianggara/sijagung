<?php

namespace backend\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "identitas_perusahaan".
 *
 * @property int $id
 * @property string $nama_perusahaan
 * @property string $jalan
 * @property string $desa
 * @property string $kecamatan
 * @property string $provinsi
 * @property string $kota
 * @property string $koordinat
 * @property int $telepon
 * @property int $fax
 * @property string $email
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 * @property Provinsi $provinsi
 * @property KotaKabupaten $kota_kabupaten
 * @property Kecamatan $kecamatan
 * @property Kelurahan $kelurahan

 */
class IdentitasPerusahaan extends \yii\db\ActiveRecord
{

    public function extraFields() {
        return ['provinsiLabel','kotaLabel', 'kecamatanLabel', 'kelurahan'];
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'identitas_perusahaan';
    }
	
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_perusahaan', 'jalan', 'desa', 'kecamatan', 'provinsi', 'kota', 'koordinat', 'telepon', 'fax', 'email'], 'required'],
            [['telepon', 'fax', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama_perusahaan', 'jalan', 'desa', 'kecamatan', 'provinsi', 'kota', 'koordinat', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_perusahaan' => 'Nama Perusahaan',
            'jalan' => 'Jalan',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'provinsi' => 'Provinsi',
            'kota' => 'Kota',
            'koordinat' => 'Koordinat',
            'telepon' => 'Telepon',
            'fax' => 'Fax',
            'email' => 'Email',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }
		    public function getProvinsiLabel()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'provinsi']);
    }
			    public function getKotaLabel()
    {
        return $this->hasOne(KotaKabupaten::className(), ['id' => 'kota']);
    }
			    public function getKecamatanLabel()
    {
        return $this->hasOne(Kecamatan::className(), ['id' => 'kecamatan']);
    }
			    public function getKelurahan()
    {
        return $this->hasOne(Kelurahan::className(), ['id' => 'desa']);
    }
}
