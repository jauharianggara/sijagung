<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataStok;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class LaporanPenggunaan extends Model
{
    public $nama_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;
    public $kota_kabupaten_plant;
    public $tahun_awal,$tahun_akhir,$bulan_awal,$bulan_akhir;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant','provinsi_plant','kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id','tahun_awal','tahun_akhir','bulan_awal','bulan_akhir','provinsi_gabungan_plant','pulau_plant'], 'integer'],
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
        $query = InputDataStok::find();
        $query->joinWith(['plant','plant.provinsi','plant.provinsi.provinsiGabungan','plant.provinsi.pulau']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_plant' => $this->id_plant,
            'tahun'=>$this->tahun,
        ]);
        $query->where("(tahun,bulan) >= (".$this->tahun_awal.",".$this->bulan_awal.") AND (tahun,bulan) <= (".$this->tahun_akhir.",".$this->bulan_akhir.") ");
        $query->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $query->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);
        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        return $dataProvider;
    }
}
