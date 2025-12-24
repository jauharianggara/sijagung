<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Kelurahan;

/**
 * KelurahanSearch represents the model behind the search form of `backend\models\Kelurahan`.
 */
class KelurahanSearch extends Kelurahan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_provinsi', 'id_kota_kabupaten', 'id_kecamatan', 'userAdd', 'userEdit'], 'integer'],
            [['nama', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = Kelurahan::find();

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
            'id_provinsi' => $this->id_provinsi,
            'id_kota_kabupaten' => $this->id_kota_kabupaten,
            'id_kecamatan' => $this->id_kecamatan,
            'dateAdd' => $this->dateAdd,
            'userAdd' => $this->userAdd,
            'dateEdit' => $this->dateEdit,
            'userEdit' => $this->userEdit,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}
