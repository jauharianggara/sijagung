<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataProyeksi;

/**
 * DataProyeksiSearch represents the model behind the search form of `backend\models\DataProyeksi`.
 */
class DataProyeksiSearch extends DataProyeksi
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
            [['id', 'id_plant', 'tahun', 'bulan', 'volume', 'idUserAdd', 'idUserEdit','provinsi_gabungan_plant','pulau_plant'], 'integer'],
            [['kadar_air_persen'], 'number'],
            [['dateAdd', 'dateEdit'], 'safe'],
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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_plant' => $this->id_plant,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'volume' => $this->volume,
            'kadar_air_persen' => $this->kadar_air_persen,
            'idUserAdd' => $this->idUserAdd,
            'dateAdd' => $this->dateAdd,
            'idUserEdit' => $this->idUserEdit,
            'dateEdit' => $this->dateEdit,
        ]);

        return $dataProvider;
    }

    public function searchIndex($params)
    {
        $query = DataProyeksi::find();
        $query->groupBy(['tahun','id_plant']);
        $query->joinWith(['plant','plant.plant.provinsi','plant.plant.provinsi.provinsiGabungan','plant.plant.provinsi.pulau']);

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
            'id' => $this->id,
            'id_plant' => $this->id_plant,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'volume' => $this->volume,
            'kadar_air_persen' => $this->kadar_air_persen,
            'idUserAdd' => $this->idUserAdd,
            'dateAdd' => $this->dateAdd,
            'idUserEdit' => $this->idUserEdit,
            'dateEdit' => $this->dateEdit,
        ]);

        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        return $dataProvider;
    }
}
