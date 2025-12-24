<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataPembelianSumber;

/**
 * InputDataPembelianSumberSearch represents the model behind the search form of `backend\models\InputDataPembelianSumber`.
 */
class InputDataPembelianSumberSearch extends InputDataPembelianSumber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_input_data_pembelian', 'id_provinsi', 'userAdd', 'userEdit'], 'integer'],
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
        $query = InputDataPembelianSumber::find();

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
            'id_input_data_pembelian' => $this->id_input_data_pembelian,
            'id_provinsi' => $this->id_provinsi,
            'dateAdd' => $this->dateAdd,
            'userAdd' => $this->userAdd,
            'dateEdit' => $this->dateEdit,
            'userEdit' => $this->userEdit,
        ]);

        return $dataProvider;
    }
}
