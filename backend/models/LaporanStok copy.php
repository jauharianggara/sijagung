<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataStok;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class LaporanStok extends InputDataStok
{
    public $nama_plant;
    public $provinsi_plant;
    public $kota_kabupaten_plant;
    public $tahun_awal,$tahun_akhir,$bulan_awal,$bulan_akhir,$minggu_awal,$minggu_akhir;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant','provinsi_plant','kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id','tahun_awal','tahun_akhir','bulan_awal','bulan_akhir','minggu_awal','minggu_akhir'], 'integer'],
            [['bulan','nama_plant','provinsi_plant','kota_kabupaten_plant'], 'safe'],
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
        $query->joinWith(['plant']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['nama_plant'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['profil_plant.nama_plant' => SORT_ASC],
            'desc' => ['profil_plant.nama_plant' => SORT_DESC],
        ];

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

        /**
        WHERE (`YEAR`, `MONTH`) >= (2014,1)
  AND (`YEAR`, `MONTH`) <= (2015,3)
  AND `YEAR` >= 2014
  AND `YEAR` <= 2015
  **/

        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        return $dataProvider;
    }
}
