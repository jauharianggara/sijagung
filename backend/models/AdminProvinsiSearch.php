<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AdminProvinsi;

/**
 * AdminProvinsiSearch represents the model behind the search form of `backend\models\AdminProvinsi`.
 */
class AdminProvinsiSearch extends AdminProvinsi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'userAdd', 'userEdit'], 'integer'],
            [['nama', 'username', 'email', 'id_provinsi', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = AdminProvinsi::find();

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
            'id_user' => $this->id_user,
            'userAdd' => $this->userAdd,
            'dateAdd' => $this->dateAdd,
            'userEdit' => $this->userEdit,
            'dateEdit' => $this->dateEdit,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'id_provinsi', $this->id_provinsi]);

        return $dataProvider;
    }
}
