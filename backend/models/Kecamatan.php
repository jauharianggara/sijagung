<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kecamatan".
 *
 * @property int $id
 * @property int $id_provinsi
 * @property int $id_kota_kabupaten
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class Kecamatan extends \yii\db\ActiveRecord
{
	
    public function extraFields() {
        return ['kota','provinsi'];
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kecamatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_provinsi', 'id_kota_kabupaten', 'nama'], 'required'],
            [['id_provinsi', 'id_kota_kabupaten', 'userAdd', 'userEdit'], 'integer'],
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
}
