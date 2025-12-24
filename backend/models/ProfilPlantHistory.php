<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "profil_plant_history".
 *
 * @property int $id
 * @property int $id_plant
 * @property string $tanggal
 * @property string|null $dateAdd
 * @property int|null $userAdd
 * @property string|null $dateEdit
 * @property int|null $userEdit
 * @property int $kapasitas_silo_sebelumnya
 * @property int $kapasitas_silo_sekarang
 * @property int $kapasitas_dryer_sebelumnya
 * @property int $kapasitas_dryer_sekarang
 * @property int $kapasitas_produksi_sebelumnya
 * @property int $kapasitas_produksi_sekarang
 *
 * @property ProfilPlant $plant
 */
class ProfilPlantHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profil_plant_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plant', 'userAdd', 'userEdit', 'kapasitas_silo_sebelumnya', 'kapasitas_silo_sekarang', 'kapasitas_dryer_sebelumnya', 'kapasitas_dryer_sekarang', 'kapasitas_produksi_sebelumnya', 'kapasitas_produksi_sekarang','semester','tahun'], 'integer'],
            [['tanggal', 'dateAdd', 'dateEdit'], 'safe'],
            [['id_plant'], 'exist', 'skipOnError' => true, 'targetClass' => ProfilPlant::className(), 'targetAttribute' => ['id_plant' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_plant' => 'Id Plant',
            'tanggal' => 'Tanggal',
            'dateAdd' => 'Date Add',
            'userAdd' => 'User Add',
            'dateEdit' => 'Date Edit',
            'userEdit' => 'User Edit',
            'kapasitas_silo_sebelumnya' => 'Kapasitas Silo Sebelumnya',
            'kapasitas_silo_sekarang' => 'Kapasitas Silo',
            'kapasitas_dryer_sebelumnya' => 'Kapasitas Dryer Sebelumnya',
            'kapasitas_dryer_sekarang' => 'Kapasitas Dryer',
            'kapasitas_produksi_sebelumnya' => 'Kapasitas Produksi Sebelumnya',
            'kapasitas_produksi_sekarang' => 'Kapasitas Produksi',
        ];
    }

    /**
     * Gets query for [[Plant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlant()
    {
        return $this->hasOne(ProfilPlant::className(), ['id' => 'id_plant']);
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
