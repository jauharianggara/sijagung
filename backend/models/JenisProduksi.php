<?php

namespace backend\models;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "jenis_produksi".
 *
 * @property int $id
 * @property string $nama
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class JenisProduksi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_produksi';
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
            [['nama'], 'required'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['userAdd', 'userEdit'], 'integer'],
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
            'nama' => 'Nama',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }
}
