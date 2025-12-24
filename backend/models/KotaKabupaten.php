<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kota_kabupaten".
 *
 * @property int $id
 * @property int $id_provinsi
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit

 */
class KotaKabupaten extends \yii\db\ActiveRecord
{

    public function extraFields() {
        return ['provinsi'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kota_kabupaten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_provinsi', 'nama'], 'required'],
            [['id_provinsi', 'userAdd', 'userEdit'], 'integer'],
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
            'nama' => 'Nama',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }

		public function getAdminKotaKabupatens()
		{
				return $this->hasMany(AdminKotaKabupaten::className(), ['id_kota_kabupaten' => 'id']);
		}

		public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi']);
    }
}
