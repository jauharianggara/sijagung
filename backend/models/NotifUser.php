<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "notif_user".
 *
 * @property int $id
 * @property int $id_notif
 * @property int $id_user
 * @property string|null $dateSend
 * @property string|null $dateRead
 * @property int|null $status
 * @property int|null $userAdd
 * @property string|null $dateAdd
 * @property int|null $userEdit
 * @property string|null $dateEdit
 *
 * @property Notif $notif
 * @property User $user
 */
class NotifUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notif_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_notif', 'id_user', 'status', 'userAdd', 'userEdit'], 'integer'],
            [['dateSend', 'dateRead', 'dateAdd', 'dateEdit'], 'safe'],
            [['id_notif'], 'exist', 'skipOnError' => true, 'targetClass' => Notif::className(), 'targetAttribute' => ['id_notif' => 'id']],
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
            'id_notif' => 'Id Notif',
            'id_user' => 'Id User',
            'dateSend' => 'Date Send',
            'dateRead' => 'Date Read',
            'status' => 'Status',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * Gets query for [[Notif]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotif()
    {
        return $this->hasOne(Notif::className(), ['id' => 'id_notif']);
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
