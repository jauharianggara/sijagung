<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProfilPic;

/**
 * ProfilPicSearch represents the model behind the search form of `backend\models\ProfilPic`.
 */
class ProfilPicSearch extends ProfilPic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'handphone', 'userAdd', 'userEdit', 'id_perusahaan'], 'integer'],
            [['nama', 'email', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = ProfilPic::find();

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
            'handphone' => $this->handphone,
            'dateAdd' => $this->dateAdd,
            'userAdd' => $this->userAdd,
            'dateEdit' => $this->dateEdit,
            'userEdit' => $this->userEdit,
            'id_perusahaan' => $this->id_perusahaan,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
