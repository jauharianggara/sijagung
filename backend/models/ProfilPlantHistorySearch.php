<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProfilPlantHistory;

/**
 * ProfilPlantHistorySearch represents the model behind the search form of `backend\models\ProfilPlantHistory`.
 */
class ProfilPlantHistorySearch extends ProfilPlantHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_plant', 'userAdd', 'userEdit', 'kapasitas_silo_sebelumnya', 'kapasitas_silo_sekarang', 'kapasitas_dryer_sebelumnya', 'kapasitas_dryer_sekarang', 'kapasitas_produksi_sebelumnya', 'kapasitas_produksi_sekarang','semester','tahun'], 'integer'],
            [['tanggal', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = ProfilPlantHistory::find();

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
            'tanggal' => $this->tanggal,
            'semester' => $this->semester,
            'tahun' => $this->tahun,
            'kapasitas_silo_sebelumnya' => $this->kapasitas_silo_sebelumnya,
            'kapasitas_silo_sekarang' => $this->kapasitas_silo_sekarang,
            'kapasitas_dryer_sebelumnya' => $this->kapasitas_dryer_sebelumnya,
            'kapasitas_dryer_sekarang' => $this->kapasitas_dryer_sekarang,
            'kapasitas_produksi_sebelumnya' => $this->kapasitas_produksi_sebelumnya,
            'kapasitas_produksi_sekarang' => $this->kapasitas_produksi_sekarang,
        ]);

        return $dataProvider;
    }
}
