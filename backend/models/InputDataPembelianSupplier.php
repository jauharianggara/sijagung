<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "input_data_pembelian_supplier".
 *
 * @property int $id
 * @property int $id_input_data_pembelian
 * @property int $id_supplier
 * @property string $dateAdd
 * @property int $userAdd
 * @property string $dateEdit
 * @property int $userEdit
 *
 * @property InputDataPembelian $inputDataPembelian
 * @property ProfilSupplier $supplier
 */
class InputDataPembelianSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'input_data_pembelian_supplier';
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
            [['id_input_data_pembelian', 'id_supplier'], 'required'],
            [['id_input_data_pembelian', 'id_supplier', 'userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['id_input_data_pembelian'], 'exist', 'skipOnError' => true, 'targetClass' => InputDataPembelian::className(), 'targetAttribute' => ['id_input_data_pembelian' => 'id']],
            [['id_supplier'], 'exist', 'skipOnError' => true, 'targetClass' => ProfilSupplier::className(), 'targetAttribute' => ['id_supplier' => 'id']],
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
            'id_supplier' => 'Id Supplier',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInputDataPembelian()
    {
        return $this->hasOne(InputDataPembelian::className(), ['id' => 'id_input_data_pembelian'])->inverseOf('inputDataPembelianSuppliers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(ProfilSupplier::className(), ['id' => 'id_supplier'])->inverseOf('inputDataPembelianSuppliers');
    }
}
