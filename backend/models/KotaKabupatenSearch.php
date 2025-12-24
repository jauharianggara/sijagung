<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\KotaKabupaten;

/**
 * KotaKabupatenSearch represents the model behind the search form of `backend\models\KotaKabupaten`.
 */
class KotaKabupatenSearch extends KotaKabupaten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_provinsi', 'userAdd', 'userEdit'], 'integer'],
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
        $query = KotaKabupaten::find();

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
            'dateAdd' => $this->dateAdd,
            'userAdd' => $this->userAdd,
            'dateEdit' => $this->dateEdit,
            'userEdit' => $this->userEdit,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}
