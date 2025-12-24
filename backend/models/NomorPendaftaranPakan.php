<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "nomor_pendaftaran_pakan".
 *
 * @property int $id
 * @property int $nama_plant
 * @property string $nama_dagang
 * @property int $jenis_ternak
 * @property int $jenis_pakan
 * @property string $npp
 * @property string $tanggal_terbit
 * @property string $tanggal_kadaluarsa
 * @property string $tanggal_pengajuan_ulang
 * @property int $status_produksi
 * @property string $lembaga_penguji
 * @property string $no_sertifikat_mutu_pakan
 * @property int $daerah_pemasaran
 */
class NomorPendaftaranPakan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nomor_pendaftaran_pakan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant', 'nama_dagang', 'jenis_ternak', 'jenis_pakan', 'npp', 'tanggal_terbit', 'tanggal_kadaluarsa', 'tanggal_pengajuan_ulang', 'status_produksi', 'lembaga_penguji', 'no_sertifikat_mutu_pakan', 'daerah_pemasaran'], 'required'],
            [['nama_plant', 'jenis_ternak', 'jenis_pakan', 'status_produksi', 'daerah_pemasaran'], 'integer'],
            [['tanggal_terbit', 'tanggal_kadaluarsa', 'tanggal_pengajuan_ulang'], 'safe'],
            [['nama_dagang', 'npp', 'lembaga_penguji', 'no_sertifikat_mutu_pakan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_plant' => 'Nama Plant',
            'nama_dagang' => 'Nama Dagang',
            'jenis_ternak' => 'Jenis Ternak',
            'jenis_pakan' => 'Jenis Pakan',
            'npp' => 'Npp',
            'tanggal_terbit' => 'Tanggal Terbit',
            'tanggal_kadaluarsa' => 'Tanggal Kadaluarsa',
            'tanggal_pengajuan_ulang' => 'Tanggal Pengajuan Ulang',
            'status_produksi' => 'Status Produksi',
            'lembaga_penguji' => 'Lembaga Penguji',
            'no_sertifikat_mutu_pakan' => 'No Sertifikat Mutu Pakan',
            'daerah_pemasaran' => 'Daerah Pemasaran',
        ];
    }
}
