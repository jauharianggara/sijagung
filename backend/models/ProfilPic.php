<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "profil_pic".
 *
 * @property int $id
 * @property string $nama
 * @property int $handphone
 * @property string $email
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 * @property int $id_perusahaan
 * @property IdentitasPerusahaan $identitas_perusahaan

 */
class ProfilPic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profil_pic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'handphone', 'email'], 'required'],
            [['handphone', 'userAdd', 'userEdit', 'id_perusahaan'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama', 'email'], 'string', 'max' => 255],
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
            'handphone' => 'Handphone',
            'email' => 'Email',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
            'id_perusahaan' => 'Id Perusahaan',
        ];
    }
			public function getIdentitasPerusahaan()
    {
        return $this->hasOne(IdentitasPerusahaan::className(), ['id' => 'id_perusahaan']);
    }
}
