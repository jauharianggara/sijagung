<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "notif".
 *
 * @property int $id
 * @property int $id_notif_type
 * @property string $judul
 * @property string $keterangan
 * @property int $id_user
 * @property int $id_object
 * @property string $tanggal
 * @property int|null $userAdd
 * @property string|null $dateAdd
 * @property int|null $userEdit
 * @property string|null $dateEdit
 *
 * @property NotifType $notifType
 * @property NotifUser[] $notifUsers
 * @property User $user
 */
class Notif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_notif_type', 'id_user', 'id_object', 'userAdd', 'userEdit'], 'integer'],
            [['tanggal', 'dateAdd', 'dateEdit'], 'safe'],
            [['judul', 'keterangan'], 'string', 'max' => 255],
            [['id_notif_type'], 'exist', 'skipOnError' => true, 'targetClass' => NotifType::className(), 'targetAttribute' => ['id_notif_type' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_notif_type' => 'Id Notif Type',
            'judul' => 'Judul',
            'keterangan' => 'Keterangan',
            'id_user' => 'Id User',
            'id_object' => 'Id Object',
            'tanggal' => 'Tanggal',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * Gets query for [[NotifType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifType()
    {
        return $this->hasOne(NotifType::className(), ['id' => 'id_notif_type']);
    }

    /**
     * Gets query for [[NotifUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifUsers()
    {
        return $this->hasMany(NotifUser::className(), ['id_notif' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
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
}
