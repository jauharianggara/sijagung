<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "provinsi".
 *
 * @property int $id
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class Provinsi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provinsi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['userAdd', 'userEdit','id_provinsi_gabungan','id_pulau'], 'integer'],
            [['nama','latitude','longitude'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }

    public function getAdminKotaKabupatens()
    {
        return $this->hasMany(AdminKotaKabupaten::className(), ['id_provinsi' => 'id']);
    }

    public function getAdminProvinsis()
    {
        return $this->hasMany(AdminProvinsi::className(), ['id_provinsi' => 'id']);
    }

    public function getPulau()
    {
      return $this->hasOne(Pulau::className(), ['id' => 'id_pulau']);
    }

    public function getIbuKota()
    {
      return $this->hasOne(KotaKabupaten::className(), ['id' => 'ibu_kota']);
    }

    public function getProvinsiGabungan()
    {
      return $this->hasOne(ProvinsiGabungan::className(), ['id' => 'id_provinsi_gabungan']);
    }
}
