<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProfilPlant;

/**
 * ProfilPlantSearch represents the model behind the search form of `backend\models\ProfilPlant`.
 */
class ProfilPlantSearch extends ProfilPlant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','kode_plant','userAdd', 'userEdit', 'id_perusahaan','status'], 'integer'],
            [[ 'kapasitas_silo', 'kapasitas_terpasang', 'kapasitas', 'nama_plant', 'nama_lengkap', 'telpon', 'email', 'silo', 'dryer', 'dateAdd', 'dateEdit', 'longitude', 'latitude', 'ip_nama_perusahaan', 'ip_jalan', 'ip_desa', 'ip_kecamatan', 'ip_kota', 'ip_provinsi', 'ip_koordinat', 'ip_email', 'ip_longitude', 'ip_latidude', 'pic_nama', 'pic_email', 'ip_fax', 'ip_telepon', 'pic_handphone'], 'safe'],
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
        $query = ProfilPlant::find();

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
          'kode_plant' => $this->kode_plant,
          'dateAdd' => $this->dateAdd,
          'userAdd' => $this->userAdd,
          'dateEdit' => $this->dateEdit,
          'userEdit' => $this->userEdit,
          'id_perusahaan' => $this->id_perusahaan,
          'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nama_plant', $this->nama_plant])
            ->andFilterWhere(['like', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['like', 'telpon', $this->telpon])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'silo', $this->silo])
            ->andFilterWhere(['like', 'dryer', $this->dryer])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'ip_nama_perusahaan', $this->ip_nama_perusahaan])
            ->andFilterWhere(['like', 'ip_jalan', $this->ip_jalan])
            ->andFilterWhere(['like', 'ip_desa', $this->ip_desa])
            ->andFilterWhere(['like', 'ip_kecamatan', $this->ip_kecamatan])
            ->andFilterWhere(['like', 'ip_provinsi', $this->ip_provinsi])
            ->andFilterWhere(['like', 'ip_kota', $this->ip_kota])
            ->andFilterWhere(['like', 'ip_koordinat', $this->ip_koordinat])
            ->andFilterWhere(['like', 'ip_email', $this->ip_email])
            ->andFilterWhere(['like', 'pic_nama', $this->pic_nama])
            ->andFilterWhere(['like', 'pic_email', $this->pic_email])
            ->andFilterWhere(['like', 'ip_telepon', $this->ip_telepon])
            ->andFilterWhere(['like', 'ip_fax', $this->ip_fax])
            ->andFilterWhere(['like', 'pic_handphone', $this->pic_handphone])
            ->andFilterWhere(['like', 'ip_longitude', $this->ip_longitude])
            ->andFilterWhere(['like', 'ip_latitude', $this->ip_latitude])
            ->andFilterWhere(['like', 'kapasitas_silo', $this->kapasitas_silo])
            ->andFilterWhere(['like', 'kapasitas', $this->kapasitas])
            ->andFilterWhere(['like', 'kapasitas_terpasang', $this->kapasitas_terpasang]);


        return $dataProvider;
    }
}
