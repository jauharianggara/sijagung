<?php

namespace backend\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "profil_plant".
 *
 * @property int $id
 * @property int $id_user
 * @property string $nama_plant
 * @property string $nama_lengkap
 * @property string $telpon
 * @property string $email
 * @property string $silo
 * @property string $kapasitas_silo
 * @property string $dryer
 * @property string $kapasitas
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 * @property string $longitude
 * @property string $latitude
 * @property int $id_perusahaan
 * @property string $kapasitas_terpasang
 * @property string $ip_nama_perusahaan
 * @property string $ip_jalan
 * @property string $ip_desa
 * @property string $ip_kecamatan
 * @property string $ip_provinsi
 * @property string $ip_kota
 * @property string $ip_koordinat
 * @property string $ip_telepon
 * @property string $ip_fax
 * @property string $ip_email
 * @property string $ip_longitude
 * @property string $ip_latitude
 * @property string $pic_nama
 * @property string $pic_handphone
 * @property string $pic_email
 */
class ProfilPlant extends \yii\db\ActiveRecord
{
    public $username, $jenisPorduksi;
    private $dataLama;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function extraFields()
    {
        return ['user', 'provinsi', 'kota', 'kecamatan', 'kelurahan', 'profilPlantJenisProduksi'];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            // tambah user baru
        } else {
            $this->dataLama = ProfilPlant::findOne($this->id);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // tambah user baru
        } else {
            date_default_timezone_set('Asia/Jakarta');
            $history = new ProfilPlantHistory();
            $history->id_plant = $this->id;
            $history->tanggal = date('Y-m-d H:i:s');
            $history->kapasitas_produksi_sebelumnya = $this->dataLama->kapasitas_terpasang;
            $history->kapasitas_produksi_sekarang = $this->kapasitas_terpasang;
            $history->kapasitas_silo_sebelumnya = $this->dataLama->kapasitas_silo;
            $history->kapasitas_silo_sekarang = $this->kapasitas_silo;
            $history->kapasitas_dryer_sebelumnya = $this->dataLama->kapasitas;
            $history->kapasitas_dryer_sekarang = $this->kapasitas;
            $history->tahun = date('Y');
            $history->semester = date('n') > 6 ? 2 : 1;
            $produksi = '';
            foreach ($this->profilPlantJenisProduksi as $itemProduksi) {
                $produksi .= $itemProduksi->jenisProduksi->nama.',';
            }
            $produksi = rtrim($produksi, ",");
            $history->jenis_produksi_pakan = $produksi;
            $history->save();
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profil_plant';
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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['nama_plant', 'email', 'username'];
        $scenarios[self::SCENARIO_UPDATE] = ['nama_plant', 'nama_lengkap', 'telpon', 'email', 'silo', 'kapasitas_silo', 'dryer', 'kapasitas', 'longitude', 'latitude', 'kapasitas_terpasang', 'ip_nama_perusahaan', 'ip_jalan', 'ip_desa', 'ip_kecamatan', 'ip_provinsi', 'ip_kota', 'ip_telepon', 'ip_fax', 'ip_email', 'jenisPorduksi', 'status'];
        return $scenarios;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'nama_plant', 'telpon', 'email', 'silo', 'kapasitas_silo', 'dryer', 'kapasitas', 'longitude', 'latitude', 'kapasitas_terpasang', 'ip_jalan', 'ip_desa', 'ip_kecamatan', 'ip_provinsi', 'ip_kota', 'ip_koordinat'], 'required'],
            [['id_user', 'userAdd', 'userEdit', 'id_perusahaan', 'status'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama_plant', 'nama_lengkap', 'telpon', 'email', 'silo', 'kapasitas_silo', 'dryer', 'kapasitas', 'longitude', 'latitude', 'kapasitas_terpasang', 'ip_nama_perusahaan', 'ip_jalan', 'ip_desa', 'ip_kecamatan', 'ip_provinsi', 'ip_kota', 'ip_koordinat', 'ip_telepon', 'ip_fax', 'ip_email', 'ip_longitude', 'ip_latitude', 'pic_nama', 'pic_handphone', 'pic_email', 'username'], 'string', 'max' => 255],
            [['nama_plant', 'email', 'username'], 'required', 'on' => self::SCENARIO_CREATE],
            [['nama_plant', 'telpon', 'email', 'silo', 'kapasitas_silo', 'dryer', 'kapasitas', 'longitude', 'latitude', 'kapasitas_terpasang', 'ip_jalan', 'ip_desa', 'ip_kecamatan', 'ip_provinsi', 'ip_kota', 'ip_telepon', 'ip_fax', 'ip_email', 'jenisPorduksi'], 'required', 'on' => self::SCENARIO_UPDATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'nama_plant' => 'Nama Plant',
            'nama_lengkap' => 'Nama Lengkap',
            'telpon' => 'No Handphone PIC',
            'email' => 'Email',
            'silo' => 'Silo',
            'kapasitas_silo' => 'Kapasitas Silo',
            'dryer' => 'Dryer',
            'kapasitas' => 'Kapasitas',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'id_perusahaan' => 'Id Perusahaan',
            'kapasitas_terpasang' => 'Kapasitas Produksi Terpasang',
            'ip_nama_perusahaan' => 'Nama Perusahaan',
            'ip_jalan' => 'Jalan',
            'ip_desa' => 'Desa',
            'ip_kecamatan' => 'Kecamatan',
            'ip_provinsi' => 'Provinsi',
            'ip_kota' => 'Kota',
            'ip_koordinat' => 'Koordinat',
            'ip_telepon' => 'Telepon',
            'ip_fax' => 'Fax',
            'ip_email' => 'Email',
            'ip_longitude' => 'Longitude',
            'ip_latitude' => 'Latitude',
            'pic_nama' => 'Pic Nama',
            'pic_handphone' => 'Pic Handphone',
            'pic_email' => 'Pic Email',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'ip_provinsi']);
    }
    public function getKota()
    {
        return $this->hasOne(KotaKabupaten::className(), ['id' => 'ip_kota']);
    }
    public function getKecamatan()
    {
        return $this->hasOne(Kecamatan::className(), ['id' => 'ip_kecamatan']);
    }

    public function getKelurahan()
    {
        return $this->hasOne(Kelurahan::className(), ['id' => 'ip_desa']);
    }
    public function getProfilPlantJenisProduksi()
    {
        return $this->hasMany(ProfilPlantJenisProduksi::className(), ['id_profil_plant' => 'id']);
    }

    public function getProfilPlantHistory()
    {
        return $this->hasMany(ProfilPlantHistory::className(), ['id_plant' => 'id']);
    }
}
