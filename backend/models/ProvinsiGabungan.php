<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "provinsi_gabungan".
 *
 * @property int $id
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class ProvinsiGabungan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provinsi_gabungan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateAdd', 'dateEdit'], 'safe'],
            [['userAdd', 'userEdit','id_pulau'], 'integer'],
            [['nama'], 'string', 'max' => 255],
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
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }

    public function behaviors()
    {
        return [
          [
            'class' => BlameableBehavior::className(),
            'createdByAttribute' => 'idUserAdd',
            'updatedByAttribute' => 'idUserEdit',
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
