<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataPembelian;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class InputDataPembelianSearch extends InputDataPembelian
{
    public $nama_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;

    public $kota_kabupaten_plant;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant','provinsi_plant','kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id','tahun','provinsi_gabungan_plant','pulau_plant'], 'integer'],
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
        $query = InputDataPembelian::find();
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

        $query->andFilterWhere(['in', 'bulan', $this->bulan])
        ->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        return $dataProvider;
    }

    public function searchPembelian($params)
    {
        $query = InputDataPembelian::find()
        ->select('bulan,tahun, sum(quantity_terima) as quantity_terima')
        ->groupBy('bulan');

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
            'bulan' => $this->bulan,
            'tahun'=>$this->tahun,
        ]);
        return $dataProvider;
    }
}
