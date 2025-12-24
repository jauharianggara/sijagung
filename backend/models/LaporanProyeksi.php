<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\models\DataProyeksi;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class LaporanProyeksi extends DataProyeksi
{

    public $id_plant;

    public $nama_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;

    public $kota_kabupaten_plant;
    public $tahun_awal,$tahun_akhir,$bulan_awal,$bulan_akhir,$minggu_awal,$minggu_akhir;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant','provinsi_plant','kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id','tahun_awal','tahun_akhir','bulan_awal','bulan_akhir','minggu_awal','minggu_akhir','provinsi_gabungan_plant','pulau_plant','id_plant'], 'integer'],
            [['bulan','nama_plant','provinsi_plant','kota_kabupaten_plant'], 'safe'],
            [['tahun_awal','tahun_akhir','bulan_awal','bulan_akhir'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DataProyeksi::find();
        $query->joinWith(['plant','plant.plant.provinsi','plant.plant.provinsi.provinsiGabungan','plant.plant.provinsi.pulau']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /**
        if($this->bulan_awal == date('m') && $this->tahun_awal==date("Y")){
            $this->bulan_awal =$this->bulan_awal -2;
        }

        if($this->bulan_akhir == date('m') && $this->tahun_akhir==date("Y")){
            $this->bulan_akhir =$this->bulan_akhir -2;
        }
         */
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id_plant' => $this->id_plant,
            'tahun'=>$this->tahun,
        ]);
        $query->where("(tahun,bulan) >= (".$this->tahun_awal.",".$this->bulan_awal.") AND (tahun,bulan) <= (".$this->tahun_akhir.",".$this->bulan_akhir.") ");
        $query->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $query->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);

        /**
        WHERE (`YEAR`, `MONTH`) >= (2014,1)
  AND (`YEAR`, `MONTH`) <= (2015,3)
  AND `YEAR` >= 2014
  AND `YEAR` <= 2015
  **/

        $query->andFilterWhere(['=', 'profil_plant.id', $this->id_plant]);
        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $query->andFilterWhere(['>', 'volume', 0]);
        $query->andFilterWhere(['>', 'kadar_air_persen', 0]);


        return $dataProvider;

    }
}
