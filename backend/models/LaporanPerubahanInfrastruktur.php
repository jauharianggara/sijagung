<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InputDataPembelian;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class LaporanPerubahanInfrastruktur extends ProfilPlantHistory
{
    public $nama_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;

    public $kota_kabupaten_plant;
    public $tahun_awal, $tahun_akhir, $bulan_awal, $bulan_akhir, $minggu_awal, $minggu_akhir;
    public $tanggalAwal;
    public $tanggalAkhir;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id', 'tahun_awal', 'tahun_akhir', 'bulan_awal', 'bulan_akhir', 'minggu_awal', 'minggu_akhir', 'provinsi_gabungan_plant', 'pulau_plant','tahun','semester'], 'integer'],
            [['bulan', 'nama_plant', 'provinsi_plant', 'kota_kabupaten_plant', 'tanggalAwal', 'tanggalAkhir'], 'safe'],
            [['tahun_awal', 'tahun_akhir'], 'required'],
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
        $query = ProfilPlantHistory::find();
        $query->select('kapasitas_silo_sekarang,kapasitas_dryer_sekarang,kapasitas_produksi_sekarang,max(tanggal) AS tanggal, tahun, semester, id_plant, jenis_produksi_pakan');
        $query->joinWith(['plant', 'plant.provinsi', 'plant.provinsi.provinsiGabungan', 'plant.provinsi.pulau']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->bulan_awal == date('m') && $this->tahun_awal == date("Y")) {
            $this->bulan_awal = $this->bulan_awal - 2;
        }

        if ($this->bulan_akhir == date('m') && $this->tahun_akhir == date("Y")) {
            $this->bulan_akhir = $this->bulan_akhir - 2;
        }


        //$jumlahHari = cal_days_in_month(CAL_GREGORIAN, $this->bulan_akhir, $this->tahun_akhir);
        $this->tanggalAwal = new \DateTime($this->tahun_awal . '-01-01');
        $this->tanggalAkhir = new \DateTime($this->tahun_akhir . '-12-31');

        // subquery
        $subQuery = ProfilPlantHistory::find();
        $subQuery->select('max(tanggal) AS tanggal');
        $subQuery->andFilterWhere([
            'id_plant' => $this->id_plant,
        ]);
        $subQuery->andFilterWhere(['BETWEEN', 'tanggal', $this->tanggalAwal->format('Y-m-d'), $this->tanggalAkhir->format('Y-m-d')]);
        $subQuery->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $subQuery->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $subQuery->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        $subQuery->groupBy(['tahun','semester','id_plant']);


        // grid filtering conditions
        $query->andFilterWhere([
            'id_plant' => $this->id_plant,
        ]);
        //$query->andFilterWhere(['BETWEEN', 'tanggal', $this->tanggalAwal->format('Y-m-d'), $this->tanggalAkhir->format('Y-m-d')]);
        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        $query->andFilterWhere(['IN', 'tanggal', $subQuery]);

        $query->groupBy(['tahun','semester','id_plant']);


        return $dataProvider;
    }
}
