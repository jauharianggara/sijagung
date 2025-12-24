<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "profil_plant_jenis_produksi".
 *
 * @property int $id
 * @property string $id_profil_plant
 * @property string $id_jenis_produksi
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class ProfilPlantJenisProduksi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profil_plant_jenis_produksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_profil_plant', 'id_jenis_produksi'], 'required'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['userAdd', 'userEdit', 'id_profil_plant', 'id_jenis_produksi'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_profil_plant' => 'ID Profil Plant',
            'id_jenis_produksi' => 'ID Jenis Produksi',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }

    public function getProfilPlant()
    {
        return $this->hasOne(ProfilPlant::className(), ['id' => 'id_profil_plant']);
    }
		public function getJenisProduksi()
    {
        return $this->hasOne(JenisProduksi::className(), ['id' => 'id_jenis_produksi']);
	  }
}
