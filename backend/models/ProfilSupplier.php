<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "profil_supplier".
 *
 * @property int $id
 * @property int $id_plant
 * @property string $nama
 * @property string $alamat
 * @property string $id_provinsi
 * @property string $id_kota_kabupaten
 * @property string $id_kecamatan
 * @property string $id_kelurahan
 * @property int $userAdd
 * @property string $dateAdd
 * @property int $userEdit
 * @property string $dateEdit
 *
 * @property ProfilPlant $plant
 * @property Provinsi $provinsi
 * @property KotaKabupaten $kotaKabupaten
 * @property Kecamatan $kecamatan
 * @property Kelurahan $kelurahan
 */
class ProfilSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profil_supplier';
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
            [['id_plant','nama', 'alamat','id_provinsi','id_kota_kabupaten','id_kecamatan','id_kelurahan'], 'required'],
            [['id_plant', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama', 'alamat','telepon'], 'string', 'max' => 255],
            [['id_provinsi'], 'string', 'max' => 2],
            [['id_kota_kabupaten'], 'string', 'max' => 4],
            [['id_kecamatan'], 'string', 'max' => 7],
            [['id_kelurahan'], 'string', 'max' => 10],
            [['id_plant'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_plant' => 'id']],
            [['id_provinsi'], 'exist', 'skipOnError' => true, 'targetClass' => Provinsi::className(), 'targetAttribute' => ['id_provinsi' => 'id']],
            [['id_kota_kabupaten'], 'exist', 'skipOnError' => true, 'targetClass' => KotaKabupaten::className(), 'targetAttribute' => ['id_kota_kabupaten' => 'id']],
            [['id_kecamatan'], 'exist', 'skipOnError' => true, 'targetClass' => Kecamatan::className(), 'targetAttribute' => ['id_kecamatan' => 'id']],
            [['id_kelurahan'], 'exist', 'skipOnError' => true, 'targetClass' => Kelurahan::className(), 'targetAttribute' => ['id_kelurahan' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_plant' => 'Id Plant',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'id_provinsi' => 'Id Provinsi',
            'id_kota_kabupaten' => 'Id Kota Kabupaten',
            'id_kecamatan' => 'Id Kecamatan',
            'id_kelurahan' => 'Id Kelurahan',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlant()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'id_plant']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKotaKabupaten()
    {
        return $this->hasOne(KotaKabupaten::className(), ['id' => 'id_kota_kabupaten']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKecamatan()
    {
        return $this->hasOne(Kecamatan::className(), ['id' => 'id_kecamatan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelurahan()
    {
        return $this->hasOne(Kelurahan::className(), ['id' => 'id_kelurahan']);
    }

    public function getInputDataPembelianSuppliers()
    {
        return $this->hasMany(InputDataPembelianSupplier::className(), ['id_supplier' => 'id']);
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
