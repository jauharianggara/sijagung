<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NotifUser;

/**
 * NomorPendaftaranPakanSearch represents the model behind the search form of `backend\models\NomorPendaftaranPakan`.
 */
class NotifSearch extends NotifUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          [['status', 'id_user'], 'integer'],
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
        $query = NotifUser::find();
        $query->joinWith(['notif']);

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
            'notif_user.id_user' => $this->id_user,
            'notif_user.status' => $this->status,
        ]);
        return $dataProvider;
    }
}
