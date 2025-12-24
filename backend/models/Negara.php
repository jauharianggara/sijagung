<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "negara".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $userAdd
 * @property string $dateAdd
 * @property int $userEdit
 * @property string $dateEdit
 *
 * @property UnitUsahaPemasaranLuarNegeri[] $unitUsahaPemasaranLuarNegeris
 */
class Negara extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'negara';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userAdd', 'userEdit'], 'integer'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['kode'], 'string', 'max' => 3],
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
            'kode' => 'Kode',
            'nama' => 'Nama',
            'userAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'userEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitUsahaPemasaranLuarNegeris()
    {
        return $this->hasMany(UnitUsahaPemasaranLuarNegeri::className(), ['id_negara' => 'id']);
    }
}
