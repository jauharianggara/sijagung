<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "admin_kota_kabupaten".
 *
 * @property int $id
 * @property string $id_provinsi
 * @property string $id_kota_kabupaten
 * @property string $nama
 * @property string $username
 * @property string $email
 * @property int $id_user
 * @property int $userAdd
 * @property string $dateAdd
 * @property int $userEdit
 * @property string $dateEdit
 *
 * @property KotaKabupaten $kotaKabupaten
 * @property User $user
 * @property Provinsi $provinsi
 */
class AdminKotaKabupaten extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_kota_kabupaten';
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
            [['id_user', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['id_provinsi'], 'string', 'max' => 2],
            [['id_kota_kabupaten'], 'string', 'max' => 4],
            [['nama', 'username', 'email','nama_pic','hp_pic'], 'string', 'max' => 255],
            [['id_kota_kabupaten'], 'exist', 'skipOnError' => true, 'targetClass' => KotaKabupaten::className(), 'targetAttribute' => ['id_kota_kabupaten' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['id_provinsi'], 'exist', 'skipOnError' => true, 'targetClass' => Provinsi::className(), 'targetAttribute' => ['id_provinsi' => 'id']],
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
            'nama_pic' => 'Nama PIC',
            'hp_pic' => 'HP PIC',
            'username' => 'Username',
            'email' => 'Email',
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
    public function getKotaKabupaten()
    {
        return $this->hasOne(KotaKabupaten::className(), ['id' => 'id_kota_kabupaten'])->inverseOf('adminKotaKabupatens');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user'])->inverseOf('adminKotaKabupatens');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi'])->inverseOf('adminKotaKabupatens');
    }
}
