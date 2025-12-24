<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_provinsi".
 *
 * @property int $id
 * @property string $nama
 * @property string $username
 * @property string $email
 * @property string $id_provinsi
 * @property int $id_user
 * @property int $userAdd
 * @property string $dateAdd
 * @property int $userEdit
 * @property string $dateEdit
 *
 * @property Provinsi $provinsi
 * @property User $user
 */
class AdminProvinsi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_provinsi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['id_user', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama', 'username', 'email','nama_pic','hp_pic'], 'string', 'max' => 255],
            [['id_provinsi'], 'string', 'max' => 2],
            [['id_provinsi'], 'exist', 'skipOnError' => true, 'targetClass' => Provinsi::className(), 'targetAttribute' => ['id_provinsi' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
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
            'nama_pic' => 'Nama PIC',
            'hp_pic' => 'HP PIC',
            'username' => 'Username',
            'email' => 'Email',
            'id_provinsi' => 'Id Provinsi',
            'id_user' => 'Id User',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi'])->inverseOf('adminProvinsis');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user'])->inverseOf('adminProvinsis');
    }
}
