<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IdentitasPerusahaan;

/**
 * IdentitasPerusahaanSearch represents the model behind the search form of `backend\models\IdentitasPerusahaan`.
 */
class IdentitasPerusahaanSearch extends IdentitasPerusahaan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'telepon', 'fax', 'userAdd', 'userEdit'], 'integer'],
            [['nama_perusahaan', 'jalan', 'desa', 'kecamatan', 'provinsi', 'kota', 'koordinat', 'email', 'dateAdd', 'dateEdit'], 'safe'],
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
        $query = IdentitasPerusahaan::find();

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
            'telepon' => $this->telepon,
            'fax' => $this->fax,
            'dateAdd' => $this->dateAdd,
            'userAdd' => $this->userAdd,
            'dateEdit' => $this->dateEdit,
            'userEdit' => $this->userEdit,
        ]);

        $query->andFilterWhere(['like', 'nama_perusahaan', $this->nama_perusahaan])
            ->andFilterWhere(['like', 'jalan', $this->jalan])
            ->andFilterWhere(['like', 'desa', $this->desa])
            ->andFilterWhere(['like', 'kecamatan', $this->kecamatan])
            ->andFilterWhere(['like', 'provinsi', $this->provinsi])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'koordinat', $this->koordinat])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
