<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataStok;

/**
 * InputDataStokSearch represents the model behind the search form of `backend\models\InputDataStok`.
 */
class InputDataStokSearch extends InputDataStok
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
            [['tahun','provinsi_gabungan_plant','pulau_plant'], 'integer'],
            [['nama_plant','provinsi_plant','kota_kabupaten_plant','bulan'], 'safe'],
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
        $dataProvider->sort->attributes['nama_plant'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['profil_plant.nama_plant' => SORT_ASC],
            'desc' => ['profil_plant.nama_plant' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tahun'=>$this->tahun,
            'id_plant' => $this->id_plant,
        ]);

        $query->andFilterWhere(['in', 'bulan', $this->bulan])
            ->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
            $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
            $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        return $dataProvider;
    }

    public function searchStokTahun($params)
    {
        $query = InputDataStok::find()
        ->select('bulan,tahun, sum(jumlah_stok) as jumlah_stok')
        ->groupBy('bulan');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tahun'=>$this->tahun,
            'bulan' => $this->bulan,
        ]);

        return $dataProvider;
    }
}
