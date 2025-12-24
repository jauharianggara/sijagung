<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "input_data_pembelian_sumber".
 *
 * @property int $id
 * @property int $id_input_data_pembelian
 * @property int $id_provinsi
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 */
class InputDataPembelianSumber extends \yii\db\ActiveRecord
{
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
  

    public function extraFields() {
        return ['inputDataPembelian','provinsi'];
    }
  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'input_data_pembelian_sumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_input_data_pembelian', 'id_provinsi'], 'required'],
            [['id_input_data_pembelian', 'id_provinsi', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_input_data_pembelian' => 'Id Input Data Pembelian',
            'id_provinsi' => 'Id Provinsi',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }
		    public function getInputDataPembelian()
    {
        return $this->hasOne(InputDataPembelian::className(), ['id' => 'id_input_data_pembelian']);
    }
		    public function getProvinsi()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'id_provinsi']);
    }
}
