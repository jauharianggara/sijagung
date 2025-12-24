<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\NomorPendaftaranPakan;

/**
 * NomorPendaftaranPakanSearch represents the model behind the search form of `backend\models\NomorPendaftaranPakan`.
 */
class NomorPendaftaranPakanSearch extends NomorPendaftaranPakan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama_plant', 'jenis_ternak', 'jenis_pakan', 'status_produksi', 'daerah_pemasaran'], 'integer'],
            [['nama_dagang', 'npp', 'tanggal_terbit', 'tanggal_kadaluarsa', 'tanggal_pengajuan_ulang', 'lembaga_penguji', 'no_sertifikat_mutu_pakan'], 'safe'],
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
        $query = NomorPendaftaranPakan::find();

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
            'nama_plant' => $this->nama_plant,
            'jenis_ternak' => $this->jenis_ternak,
            'jenis_pakan' => $this->jenis_pakan,
            'tanggal_terbit' => $this->tanggal_terbit,
            'tanggal_kadaluarsa' => $this->tanggal_kadaluarsa,
            'tanggal_pengajuan_ulang' => $this->tanggal_pengajuan_ulang,
            'status_produksi' => $this->status_produksi,
            'daerah_pemasaran' => $this->daerah_pemasaran,
        ]);

        $query->andFilterWhere(['like', 'nama_dagang', $this->nama_dagang])
            ->andFilterWhere(['like', 'npp', $this->npp])
            ->andFilterWhere(['like', 'lembaga_penguji', $this->lembaga_penguji])
            ->andFilterWhere(['like', 'no_sertifikat_mutu_pakan', $this->no_sertifikat_mutu_pakan]);

        return $dataProvider;
    }
}
