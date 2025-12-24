<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "setting".
 *
 * @property int $lock_data_pembelian
 * @property int $lock_data_stok
 * @property int|null $idUserAdd
 * @property string|null $dateAdd
 * @property int|null $idUserEdit
 * @property string|null $dateEdit
 * @property int $id
 * @property string $lock_data_proyeksi
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lock_data_pembelian', 'lock_data_stok', 'idUserAdd', 'idUserEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['lock_data_proyeksi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lock_data_pembelian' => 'Lock Data Pembelian',
            'lock_data_stok' => 'Lock Data Stok',
            'idUserAdd' => 'Id User Add',
            'dateAdd' => 'Date Add',
            'idUserEdit' => 'Id User Edit',
            'dateEdit' => 'Date Edit',
            'id' => 'ID',
            'lock_data_proyeksi' => 'Lock Data Proyeksi',
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
