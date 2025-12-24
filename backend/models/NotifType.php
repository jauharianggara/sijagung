<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "notif_type".
 *
 * @property int $id
 * @property string $nama
 * @property string $route
 * @property int|null $userAdd
 * @property string|null $dateAdd
 * @property int|null $userEdit
 * @property string|null $dateEdit
 *
 * @property Notif[] $notifs
 */
class NotifType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notif_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['nama', 'route'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'route' => 'Route',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * Gets query for [[Notifs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotifs()
    {
        return $this->hasMany(Notif::className(), ['id_notif_type' => 'id']);
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
