<?php

namespace backend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "data_proyeksi".
 *
 * @property int $id
 * @property int|null $id_plant
 * @property int|null $tahun
 * @property int|null $bulan
 * @property int|null $volume
 * @property float|null $kadar_air_persen
 * @property int|null $idUserAdd
 * @property string|null $dateAdd
 * @property int|null $idUserEdit
 * @property string|null $dateEdit
 *
 * @property User $idUserAdd0
 * @property User $idUserEdit0
 * @property User $plant
 */
class DataProyeksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_proyeksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_plant', 'tahun', 'bulan', 'volume', 'idUserAdd', 'idUserEdit'], 'integer'],
            [['kadar_air_persen'], 'number'],
            [['dateAdd', 'dateEdit'], 'safe'],
            [['idUserAdd'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['idUserAdd' => 'id']],
            [['idUserEdit'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['idUserEdit' => 'id']],
            [['id_plant'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_plant' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_plant' => 'Plant',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'volume' => 'Volume Kebutuhan KA terima (Kg)',
            'konversiVolume14' => 'Konversi Volume 14 %',
            'konversiVolume15' => 'Konversi Volume 15 %',
            'kadar_air_persen' => 'Kadar Air(%)',
            'idUserAdd' => 'User Add',
            'dateAdd' => 'Date Add',
            'idUserEdit' => 'User Edit',
            'dateEdit' => 'Date Edit',
        ];
    }

    /**
     * Gets query for [[IdUserAdd0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUserAdd0()
    {
        return $this->hasOne(User::className(), ['id' => 'idUserAdd']);
    }

    /**
     * Gets query for [[IdUserEdit0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUserEdit0()
    {
        return $this->hasOne(User::className(), ['id' => 'idUserEdit']);
    }

    /**
     * Gets query for [[Plant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlant()
    {
        return $this->hasOne(User::className(), ['id' => 'id_plant']);
    }

    public function getKonversiVolume15()
    {
        $kadarStandart = 15;
        $kadarKonversi = $this->volume;
        //echo 'KA Terima :'.$this->ka_terima.' -- ';
        if ($this->kadar_air_persen > $kadarStandart) {

            for ($i = $this->kadar_air_persen; $i > $kadarStandart; $i--) {
                $kadarBefore = $kadarKonversi;
                $kadarKonversi = $kadarKonversi - ($kadarKonversi * 0.015);
                //echo $i.' ';
                //echo $kadarKonversi.'  -- ';
            }
            $num = $i;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                //echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
                $kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
            }
            // koma
        } else if ($this->kadar_air_persen < $kadarStandart) {
            $num = $this->kadar_air_persen;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                $kadarStandart++;
                for ($i = $this->kadar_air_persen; $i <= $kadarStandart; $i++) {
                    $kadarBefore = $kadarKonversi;
                    $kadarKonversi = $kadarKonversi + ($kadarKonversi * 0.015);
                    //echo $kadarKonversi.'  -- ';
                }
            } else {
                for ($i = $this->kadar_air_persen; $i < $kadarStandart; $i++) {
                    $kadarBefore = $kadarKonversi;
                    $kadarKonversi = $kadarKonversi + ($kadarKonversi * 0.015);
                    //echo $kadarKonversi.'  -- ';
                }
            }


            //echo '<br>';
            $num = $i;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                //echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
                $kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
            }
        }
        return $kadarKonversi;
    }

    public function getKonversiVolume14()
    {
        $kadarStandart = 14;
        $kadarKonversi = $this->volume;
        //echo 'KA Terima :'.$this->ka_terima.' -- ';
        if ($this->kadar_air_persen > $kadarStandart) {

            for ($i = $this->kadar_air_persen; $i > $kadarStandart; $i--) {
                $kadarBefore = $kadarKonversi;
                $kadarKonversi = $kadarKonversi - ($kadarKonversi * 0.015);
                //echo $i.' ';
                //echo $kadarKonversi.'  -- ';
            }
            $num = $i;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                //echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
                $kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
            }
            // koma
        } else if ($this->kadar_air_persen < $kadarStandart) {
            $num = $this->kadar_air_persen;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                $kadarStandart++;
                for ($i = $this->kadar_air_persen; $i <= $kadarStandart; $i++) {
                    $kadarBefore = $kadarKonversi;
                    $kadarKonversi = $kadarKonversi + ($kadarKonversi * 0.015);
                    //echo $kadarKonversi.'  -- ';
                }
            } else {
                for ($i = $this->kadar_air_persen; $i < $kadarStandart; $i++) {
                    $kadarBefore = $kadarKonversi;
                    $kadarKonversi = $kadarKonversi + ($kadarKonversi * 0.015);
                    //echo $kadarKonversi.'  -- ';
                }
            }


            //echo '<br>';
            $num = $i;
            $whole = (int) $num; // 5
            $frac = $num - $whole; // .7
            //echo $frac.'<br>';
            if ($frac > 0) {
                //echo $kadarBefore.' * '.$frac.' * 0.015'.'<br>';
                $kadarKonversi = $kadarBefore - ($kadarBefore * $frac * 0.015);
            }
        }

        return $kadarKonversi;
    }

    public function getTotal14TahunIni()
    {
        $dataProyeksiTahunSebelumNya = DataProyeksi::find()->where(['tahun' => $this->tahun, 'id_plant' => $this->id_plant])->all();
        $total = 0;
        foreach ($dataProyeksiTahunSebelumNya as $key => $data) {
            $total += $data->volume;
        }
        return $total;
    }

    public function getTotal15TahunIni()
    {
        $dataProyeksiTahunSebelumNya = DataProyeksi::find()->where(['tahun' => $this->tahun, 'id_plant' => $this->id_plant])->all();
        $total = 0;
        foreach ($dataProyeksiTahunSebelumNya as $key => $data) {
            $total += $data->konversiVolume15;
        }
        return $total;
    }

    public function getPertumbuhan14(){
        $pertumbuhan = (($this->total14TahunIni - $this->total14TahunSebelumnya) / $this->total14TahunIni)*100;
        return $pertumbuhan;
    }

    public function getTotal14TahunSebelumnya()
    {
        $dataProyeksiTahunSebelumNya = DataProyeksi::find()->where(['tahun' => $this->tahun - 1, 'id_plant' => $this->id_plant])->all();
        $total = 0;
        foreach ($dataProyeksiTahunSebelumNya as $key => $data) {
            $total += $data->volume;
        }
        return $total;
    }

    public function getTotal15TahunSebelumnya()
    {
        $dataProyeksiTahunSebelumNya = DataProyeksi::find()->where(['tahun' => $this->tahun - 1, 'id_plant' => $this->id_plant])->all();
        $total = 0;
        foreach ($dataProyeksiTahunSebelumNya as $key => $data) {
            $total += $data->konversiVolume15;
        }
        return $total;
    }

    public function getPertumbuhan15(){
        $pertumbuhan = (($this->total15TahunIni - $this->total15TahunSebelumnya) / $this->total15TahunIni)*100;
        return $pertumbuhan;
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

    public function getBulanLabel()
    {
        $label = array(
            "0" => "",
            "1" => "Januari",
            "2" => "Februari",
            "3" => "Maret",
            "4" => "April",
            "5" => "Mei",
            "6" => "Juni",
            "7" => "Juli",
            "8" => "Agustus",
            "9" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember",
        );
        return $label[$this->bulan];
    }
}
