<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\models\InputDataStok;

/**
 * InputDataStokSearch represents the model behind the search form of `backend\models\InputDataStok`.
 */
class LandingStok extends InputDataStok
{
    public $wilayah;
    public $nama_plant;
    public $provinsi_plant;
    public $kota_kabupaten_plant;
    public $tahun_awal, $tahun_akhir, $bulan_awal, $bulan_akhir, $minggu_awal, $minggu_akhir;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id', 'tahun', 'wilayah'], 'integer'],
            [['bulan', 'nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'safe'],
            [['tahun_awal', 'tahun_akhir', 'bulan_awal', 'bulan_akhir', 'minggu_awal', 'minggu_akhir'], 'integer'],

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
        $query = InputDataStok::find();
        $query->joinWith(['plant']);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $dataProvider->sort->attributes['nama_plant'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['profil_plant.nama_plant' => SORT_ASC],
            'desc' => ['profil_plant.nama_plant' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $tanggalHariIni = new \DateTime();

        $tanggalAwal = new \DateTime();
        $tanggalAwal->setDate($this->tahun_awal, $this->bulan_awal, 1);

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->setDate($this->tahun_akhir, $this->bulan_akhir, 1);

        $tanggal = new \DateTime();
        $tanggal->modify('-3 month');
        if ($tanggalAwal > $tanggal) {
            $this->bulan_awal = (int) $tanggal->format('m');
            $this->tahun_awal = $tanggal->format('Y');
        }

        $diffAkhir = $tanggal->diff($tanggalAkhir);
        if ($tanggalAkhir > $tanggal) {
            $this->bulan_akhir = (int) $tanggal->format('m');
            $this->tahun_akhir = $tanggal->format('Y');
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_plant' => $this->id_plant,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
        ]);


        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $query->where("(tahun,bulan) >= (" . $this->tahun_awal . "," . $this->bulan_awal . ") AND (tahun,bulan) <= (" . $this->tahun_akhir . "," . $this->bulan_akhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $query->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);

        $result = [];
        if ($this->wilayah == 1) {
            // nasional
            $pulau = Pulau::find()->all();
            foreach ($pulau as $item) {
                $result[$item->id] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                ];
            }
        } else if ($this->wilayah == 2) {
            // provinsi
            $pulau = Pulau::find()->orderBy(['id' => SORT_ASC])->all();
            foreach ($pulau as $item) {
                $result[$item->id] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                ];
                foreach ($item->provinsi as $provinsi) {
                    $result[$item->id]['provinsi'][$provinsi->id] = [
                        'id' => $provinsi->id,
                        'nama' => ucwords(strtolower($provinsi->nama)),
                    ];
                }

            }
        } else if ($this->wilayah == 3) {
            // provinsi gabungan
            $pulau = Pulau::find()->orderBy(['id' => SORT_ASC])->all();
            foreach ($pulau as $item) {
                $result[$item->id] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                ];
                foreach ($item->provinsiGabungan as $provinsiGabungan) {
                    $result[$item->id]['provinsi'][$provinsiGabungan->id] = [
                        'id' => $provinsiGabungan->id,
                        'nama' => ucwords(strtolower($provinsiGabungan->nama)),
                    ];
                }

            }
        } else {
            // plant
            $plant = ProfilPlant::find()->orderBy(['nama_plant' => SORT_ASC])->all();
            foreach ($plant as $item) {
                $result[$item->id_user] = [
                    'id' => $item->id_user,
                    'nama' => ucwords(strtolower($item->nama_plant)),
                ];
            }
        }

        $tanggalAwal = new \DateTime($this->tahun_awal . "-" . $this->bulan_awal . "-01");
        $tanggalAkhir = new \DateTime($this->tahun_akhir . "-" . $this->bulan_akhir . "-01");
        $interval = $tanggalAwal->diff($tanggalAkhir);
        $intervalBulan = $interval->m + ($interval->y * 12);

        foreach ($dataProvider->getModels() as $item) {
            $id_pulau = $item->plant->provinsi->id_pulau;
            $id_provinsi = $item->plant->ip_provinsi;
            $id_provinsi_gabungan = $item->plant->provinsi->id_provinsi_gabungan;
            $id_plant = $item->id_plant;

            $stok = $item->jumlah_stok / 1000;
            $kecukupan_semua = $item->kecukupan_produksi;

            if ($item->jumlah_stok > 0) {
                if ($this->wilayah == 1) {
                    // nasional
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;
                } elseif ($this->wilayah == 2) {
                    // nasional
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;

                    // provinsi
                    $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;
                } elseif ($this->wilayah == 3) {
                    // nasional
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_pulau]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;

                    // provinsi gabungan
                    $result[$id_pulau]['provinsi'][$id_provinsi_gabungan]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_pulau]['provinsi'][$id_provinsi_gabungan]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_pulau]['provinsi'][$id_provinsi_gabungan]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;
                } else {
                    // plant
                    $result[$id_plant]['tahun'][$item->tahun]['bulan'][$item->bulan]['count']++;
                    $result[$id_plant]['tahun'][$item->tahun]['bulan'][$item->bulan]['stok'] += $stok;
                    $result[$id_plant]['tahun'][$item->tahun]['bulan'][$item->bulan]['kecukupan_semua'] += $kecukupan_semua;
                }
            }
        }

        foreach ($result as $id_pulau => $pulau) {
            foreach ($pulau['tahun'] as $tahun => $valueTahun) {
                foreach ($valueTahun['bulan'] as $bulan => $valueBulan) {
                    $kecukupan = 0;
                    if ($valueBulan['count'] > 0) {
                        $kecukupan = $valueBulan['kecukupan_semua'] / $valueBulan['count'];
                    }
                    $result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['kecukupan'] = $kecukupan;
                }
            }

            foreach ($pulau['provinsi'] as $id_provinsi => $provinsi) {
                foreach ($provinsi['tahun'] as $tahun => $valueTahun) {
                    foreach ($valueTahun['bulan'] as $bulan => $valueBulan) {
                        $kecukupan = 0;
                        if ($valueBulan['count'] > 0) {
                            $kecukupan = $valueBulan['kecukupan_semua'] / $valueBulan['count'];
                        }
                        $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['kecukupan'] = $kecukupan;
                    }
                }
            }
        }
       
        foreach ($result as $id_pulau => $pulau) {
            if (!isset($pulau['tahun'])) {
                unset($result[$id_pulau]);
            }
            foreach ($pulau['provinsi'] as $id_provinsi => $provinsi) {
                if (!isset($provinsi['tahun'])) {
                    unset($result[$id_pulau]['provinsi'][$id_provinsi]);
                }
            }
        }

        unset($result[null]);

        foreach ($result as $id_pulau => $pulau) {
            for ($tahun = $this->tahun_awal; $tahun <= $this->tahun_akhir; $tahun++) {
                $bulan_mulai = 1;
                $bulan_selesai = 12;

                if ($tahun == $this->tahun_awal) {
                    $bulan_mulai = $this->bulan_awal;
                }

                if ($tahun == $this->tahun_akhir) {
                    $bulan_selesai = $this->bulan_akhir;
                }

                for ($bulan = $bulan_mulai; $bulan <= $bulan_selesai; $bulan++) {
                    if (!isset($result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['stok'])) {
                        $result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['stok'] = 0;
                    }
                    if (!isset($result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['stok'])) {
                        $result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['stok'] = 0;
                    }
                    if (!isset($result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['count'])) {
                        $result[$id_pulau]['tahun'][$tahun]['bulan'][$bulan]['count'] = 0;
                    }
                }
            }

            foreach ($pulau['provinsi'] as $id_provinsi => $provinsi) {
                for ($tahun = $this->tahun_awal; $tahun <= $this->tahun_akhir; $tahun++) {
                    $bulan_mulai = 1;
                    $bulan_selesai = 12;
    
                    if ($tahun == $this->tahun_awal) {
                        $bulan_mulai = $this->bulan_awal;
                    }
    
                    if ($tahun == $this->tahun_akhir) {
                        $bulan_selesai = $this->bulan_akhir;
                    }
    
                    for ($bulan = $bulan_mulai; $bulan <= $bulan_selesai; $bulan++) {
                        if (!isset($result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['stok'])) {
                            $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun][$bulan]['bulan']['stok'] = 0;
                        }
                        if (!isset($result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['kecukupan'])) {
                            $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['kecukupan'] = 0;
                        }
                        if (!isset($result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['count'])) {
                            $result[$id_pulau]['provinsi'][$id_provinsi]['tahun'][$tahun]['bulan'][$bulan]['count'] = 0;
                        }
                    }
                }
            }
        }

        return $result;

    }
}
