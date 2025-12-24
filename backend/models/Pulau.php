<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pulau".
 *
 * @property string $id
 * @property string $nama
 * @property string $center
 */
class Pulau extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pulau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 1],
            [['nama'], 'string', 'max' => 255],
            [['center'], 'string', 'max' => 2],
            [['id'], 'unique'],
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
            'center' => 'Center',
        ];
    }

    public function getCenterObj()
    {
        return $this->hasOne(Provinsi::className(), ['id' => 'center']);
    }

    public function getProvinsi()
    {
        return $this->hasMany(Provinsi::className(), ['id_pulau' => 'id']);
    }

    public function getProvinsiGabungan()
    {
        return $this->hasMany(ProvinsiGabungan::className(), ['id_pulau' => 'id']);
    }
}
