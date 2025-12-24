<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kelurahan".
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_kota_kabupaten
 * @property int $id_kecamatan
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class Kelurahan extends \yii\db\ActiveRecord
{

    public function extraFields() {
        return ['provinsi','kota','kecamatan'];
    }
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kelurahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_provinsi', 'id_kota_kabupaten', 'id_kecamatan', 'nama'], 'required'],
            [['id', 'id_provinsi', 'id_kota_kabupaten', 'id_kecamatan', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_provinsi' => 'Id Provinsi',
            'id_kota_kabupaten' => 'Id Kota Kabupaten',
            'id_kecamatan' => 'Id Kecamatan',
            'nama' => 'Nama',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }
	    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi']);
    }
		    public function getKota()
    {
        return $this->hasOne(KotaKabupaten::className(), ['id' => 'id_kota_kabupaten']);
    }
		    public function getKecamatan()
    {
        return $this->hasOne(Kecamatan::className(), ['id' => 'id_kecamatan']);
    }
	
}
