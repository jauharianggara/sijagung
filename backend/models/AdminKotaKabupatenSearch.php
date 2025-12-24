<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AdminKotaKabupaten;

/**
 * AdminKotaKabupatenSearch represents the model behind the search form of `backend\models\AdminKotaKabupaten`.
 */
class AdminKotaKabupatenSearch extends AdminKotaKabupaten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'userAdd', 'userEdit'], 'integer'],
            [['id_kota_kabupaten', 'nama', 'username', 'email', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = AdminKotaKabupaten::find();

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

        $query->andFilterWhere(['like', 'id_kota_kabupaten', $this->id_kota_kabupaten])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
