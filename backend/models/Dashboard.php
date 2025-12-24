<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

/**
 * BeritaSearch represents the model behind the search form of `backend\models\Berita`.
 */
class Dashboard extends Model
{
    public function getDataVolumeTahunIni($params)
    {
        $query = InputDataPembelian::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        /**
        $tanggalAwal  = new \DateTime();
        $tanggalAwal->setDate($tanggalAwal->format('Y'), 1, 1);
        $tanggalAkhir  = new \DateTime();
        $tanggalAkhir->modify('-1 month');
        **/
        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-12 month');
        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-1 month');
        $result = [];
        $query->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('m') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('m') . ") ");


        foreach ($dataProvider->getModels() as $item) {
            $value = $item->konversiVolume14 / 1000;
            $result[$item->tahun . '-' . $item->bulan . '-01'] += $value;
        }
        return $result;
    }

    public function getDataProyeksiTahunIni($params)
    {
        $query = DataProyeksi::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        /**
        $tanggalAwal  = new \DateTime();
        $tanggalAwal->setDate($tanggalAwal->format('Y'), 1, 1);
        $tanggalAkhir  = new \DateTime();
        $tanggalAkhir->modify('-1 month');
        **/
        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-12 month');
        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-1 month');

        $result = [];
        $query->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('m') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('m') . ") ");


        foreach ($dataProvider->getModels() as $item) {
            $value = $item->konversiVolume14;
            $result[$item->tahun . '-' . $item->bulan . '-01'] += $value;
        }
        return $result;
    }

    public function getDataKonversiVolume14($params)
    {
        $query = InputDataPembelian::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);


        $bulanAwal = (int) date('m', strtotime('-12 months'));
        $bulanAkhir = (int) date('m');
        $tahunAwal = date('Y', strtotime('-12 months'));
        $tahunAkhir = date('Y');
        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-12 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01'] = 0;
            $tanggal->modify('+1 month');
        }

        $query->where("(tahun,bulan) >= (" . $tahunAwal . "," . $bulanAwal . ") AND (tahun,bulan) <= (" . $tahunAkhir . "," . $bulanAkhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $tahunAwal]);
        $query->andFilterWhere(['<=', 'tahun', $tahunAkhir]);

        foreach ($dataProvider->getModels() as $item) {
            $value = $item->konversiVolume14 / 1000;
            $result[$item->tahun . '-' . $item->bulan . '-01'] += $value;
        }
        return $result;
    }

    public function getDataHarga($params)
    {
        $query = InputDataPembelian::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);



        $bulanAwal = (int) date('m', strtotime('-12 months'));
        $bulanAkhir = (int) date('m');
        $tahunAwal = date('Y', strtotime('-12 months'));
        $tahunAkhir = date('Y');
        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-12 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01']['tanggal'] = $tahun . '-' . $bulan . '-01';
            $result[$tahun . '-' . $bulan . '-01']['value'] = 0;
            $result[$tahun . '-' . $bulan . '-01']['count'] = 0;
            $result[$tahun . '-' . $bulan . '-01']['average'] = 0;
            $tanggal->modify('+1 month');
        }

        $query->where("(tahun,bulan) >= (" . $tahunAwal . "," . $bulanAwal . ") AND (tahun,bulan) <= (" . $tahunAkhir . "," . $bulanAkhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $tahunAwal]);
        $query->andFilterWhere(['<=', 'tahun', $tahunAkhir]);

        foreach ($dataProvider->getModels() as $item) {
            $value = $item->konversiHarga14;
            if ($value > 0 && $item->konversiVolume14) {
                $result[$item->tahun . '-' . $item->bulan . '-01']['value'] += $value;
                $result[$item->tahun . '-' . $item->bulan . '-01']['count']++;
            }
        }

        foreach ($result as $item) {
            $average = 0;
            if ($item['count'] > 0) {
                $average = $item['value'] / $item['count'];
            }
            $result[$item['tanggal']]['average'] = round($average, 0, PHP_ROUND_HALF_UP);
        }
        //echo \json_encode($result);

        return $result;
    }

    public function getDataStok($params)
    {
        $query = InputDataStok::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $bulanAwal = (int) date('m', strtotime('-12 months'));
        $bulanAkhir = (int) date('m');
        $tahunAwal = date('Y', strtotime('-12 months'));
        $tahunAkhir = date('Y');
        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-12 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01'] = 0;
            $tanggal->modify('+1 month');
        }

        $query->where("(tahun,bulan) >= (" . $tahunAwal . "," . $bulanAwal . ") AND (tahun,bulan) <= (" . $tahunAkhir . "," . $bulanAkhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $tahunAwal]);
        $query->andFilterWhere(['<=', 'tahun', $tahunAkhir]);

        foreach ($dataProvider->getModels() as $item) {
            //$value = $item->jumlah_stok/1000;
            $value = $item->jumlah_stok;

            $result[$item->tahun . '-' . $item->bulan . '-01'] += $value;
        }
        return $result;
    }

    public function getDataKecukupan($params)
    {
        $query = InputDataStok::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $bulanAwal = (int) date('m', strtotime('-12 months'));
        $bulanAkhir = (int) date('m');
        $tahunAwal = date('Y', strtotime('-12 months'));
        $tahunAkhir = date('Y');
        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-12 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01']['tanggal'] = $tahun . '-' . $bulan . '-01';
            $result[$tahun . '-' . $bulan . '-01']['value'] = 0;
            $result[$tahun . '-' . $bulan . '-01']['count'] = 0;
            $result[$tahun . '-' . $bulan . '-01']['average'] = 0;
            $tanggal->modify('+1 month');
        }


        $query->where("(tahun,bulan) >= (" . $tahunAwal . "," . $bulanAwal . ") AND (tahun,bulan) <= (" . $tahunAkhir . "," . $bulanAkhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $tahunAwal]);
        $query->andFilterWhere(['<=', 'tahun', $tahunAkhir]);

        foreach ($dataProvider->getModels() as $item) {
            $value = $item->kecukupan_produksi;
            if ($value > 0) {
                $result[$item->tahun . '-' . $item->bulan . '-01']['value'] += $value;
                $result[$item->tahun . '-' . $item->bulan . '-01']['count']++;
            }
        }

        foreach ($result as $item) {
            $average = 0;
            if ($item['count'] > 0) {
                $average = ($item['value'] / $item['count']);
            }
            $result[$item['tanggal']]['average'] = round($average, 0, PHP_ROUND_HALF_UP);
        }


        return $result;
    }

    public function getAbsen($params)
    {
        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-12 month');
        $tanggalAwal->setDate($tanggalAwal->format('Y'), $tanggalAwal->format('m'), 1);

        $tanggalAkhir = new \DateTime();
        //$tanggalAkhir->modify('-1 month');
        $tanggalAkhir->setDate($tanggalAkhir->format('Y'), $tanggalAkhir->format('m'), 1);

        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($tanggalAwal, $interval, $tanggalAkhir);

        $pembelians = InputDataPembelian::find()
            ->select(['bulan', 'tahun', 'id_plant'])
            ->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('n') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('n') . ")")
            ->all();

        $stoks = InputDataStok::find()
            ->select(['bulan', 'tahun', 'id_plant'])
            ->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('n') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('n') . ")")
            ->all();

        $plants = ProfilPlant::find()
            ->select(['id', 'id_user', 'nama_plant'])
            ->orderBy([
                'nama_plant' => SORT_ASC
            ])
            ->all();

        $result = [];
        foreach ($plants as $plant) {
            $result[$plant->id_user]['nama'] = $plant->nama_plant;
            $result[$plant->id_user]['id'] = $plant->id;
            $result[$plant->id_user]['id_user'] = $plant->id_user;
            foreach ($period as $dt) {
                $result[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['pembelian'] = 0;
                $result[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['stok'] = 0;
            }
        }

        foreach ($pembelians as $pembelian) {
            $result[$pembelian->id_plant]['data'][$pembelian->tahun][$pembelian->bulan]['pembelian'] = 1;
        }

        foreach ($stoks as $stok) {
            $result[$stok->id_plant]['data'][$stok->tahun][$stok->bulan]['stok'] = 1;
        }
        return $result;
    }

    public function getAbsenRekap($params)
    {
        date_default_timezone_set('Asia/Jakarta');
        setlocale(LC_TIME, 'id_ID.UTF-8');
        $formatter = \Yii::$app->formatter;
        $formatter->locale = 'id-ID';

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-12 month');
        $tanggalAwal->setDate($tanggalAwal->format('Y'), $tanggalAwal->format('m'), 1);

        $tanggalAkhir = new \DateTime();
        //$tanggalAkhir->setDate($tanggalAkhir->format('Y'), $tanggalAkhir->format('m'), 1);

        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($tanggalAwal, $interval, $tanggalAkhir);
        $period = array_reverse(iterator_to_array($period));

        $jumlah_pembelian = InputDataPembelian::find()
            ->select(['tahun', 'bulan', 'count(id) as id'])
            ->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('n') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('n') . ")")
            ->groupBy([
                'tahun',
                'bulan'
            ])
            ->asArray()
            ->all();

        $jumlah_stok = InputDataStok::find()
            ->select(['tahun', 'bulan', 'count(id) as id'])
            ->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('n') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('n') . ")")
            ->groupBy([
                'tahun',
                'bulan'
            ])
            ->asArray()
            ->all();


        $jumlah_plant = ProfilPlant::find()
            ->where(['status' => 1])
            ->count();

        //print_r($jumlah_pembelian);
        //exit();
        $result = [];
        foreach ($period as $dt) {
            $tahun = $dt->format("Y");
            $bulan = (int) $dt->format("m");
            $result[$tahun][$bulan]['tahun'] = $tahun;
            $result[$tahun][$bulan]['bulan'] = $formatter->asDateTime($dt->format("Y-m-d"), 'php:F');
            $result[$tahun][$bulan]['plant'] = $jumlah_plant;
        }
        foreach ($jumlah_pembelian as $pembelian) {
            $result[$pembelian['tahun']][$pembelian['bulan']]['pembelian'] = $pembelian['id'];
        }

        foreach ($jumlah_stok as $stok) {
            $result[$stok['tahun']][$stok['bulan']]['stok'] = $stok['id'];
        }

        return $result;
    }

    public function getAbsenDetail($tahun, $bulan, $jenis, $status)
    {
        $tanggal = new \DateTime();
        $tanggal->setDate($tahun, $bulan, 1);

        $plants = ProfilPlant::find()
            ->select(['id', 'id_user', 'nama_plant'])
            ->orderBy([
                'nama_plant' => SORT_ASC
            ])
            ->all();

        if ($jenis == 1) {
            $pembelians = InputDataPembelian::find()
                ->select(['bulan', 'tahun', 'id_plant', 'dateAdd'])
                ->where([
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                ])
                ->orderBy(['dateAdd' => SORT_ASC])
                ->all();
        } elseif ($jenis == 2) {
            $stoks = InputDataStok::find()
                ->select(['bulan', 'tahun', 'id_plant', 'dateAdd'])
                ->where([
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                ])
                ->orderBy(['dateAdd' => SORT_ASC])
                ->all();
        } else {
            $pembelians = InputDataPembelian::find()
                ->select(['bulan', 'tahun', 'id_plant', 'dateAdd'])
                ->where([
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                ])
                ->orderBy(['dateAdd' => SORT_ASC])
                ->all();

            $stoks = InputDataStok::find()
                ->select(['bulan', 'tahun', 'id_plant', 'dateAdd'])
                ->where([
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                ])
                ->orderBy(['dateAdd' => SORT_ASC])
                ->all();
        }

        $result = [];
        foreach ($plants as $plant) {
            $result[$plant->id_user]['nama'] = $plant->nama_plant;
            $result[$plant->id_user]['id'] = $plant->id;
            $result[$plant->id_user]['id_user'] = $plant->id_user;
            if ($jenis == 1) {
                $result[$plant->id_user]['pembelian'] = 0;
            } elseif ($jenis == 2) {
                $result[$plant->id_user]['stok'] = 0;
            } else {
                $result[$plant->id_user]['pembelian'] = 0;
                $result[$plant->id_user]['stok'] = 0;
            }
        }

        if ($jenis == 1) {
            foreach ($pembelians as $pembelian) {
                $result[$pembelian->id_plant]['pembelian'] = 1;
                $result[$pembelian->id_plant]['tanggal'] = $pembelian['dateAdd'];
            }
        } elseif ($jenis == 2) {
            foreach ($stoks as $stok) {
                $result[$stok->id_plant]['stok'] = 1;
                $result[$stok->id_plant]['tanggal'] = $stok['dateAdd'];

            }
        } else {
            foreach ($pembelians as $pembelian) {
                $result[$pembelian->id_plant]['pembelian'] = 1;
                $result[$pembelian->id_plant]['pembelian']['tanggal'] = $pembelian['dateAdd'];
            }

            foreach ($stoks as $stok) {
                $result[$stok->id_plant]['stok'] = 1;
                $result[$stok->id_plant]['pembelian']['tanggal'] = $stok['dateAdd'];
            }
        }

        if ($status == 1) {
            if ($jenis == 1) {
                foreach ($result as $key => $item) {
                    if ($item['pembelian'] == 0) {
                        unset($result[$key]);
                    }
                }
                ArrayHelper::multisort($result, ['tanggal'], [SORT_ASC]);
            } elseif ($jenis == 2) {
                foreach ($result as $key => $item) {
                    if ($item['stok'] == 0) {
                        unset($result[$key]);
                    }
                }
                ArrayHelper::multisort($result, ['tanggal'], [SORT_ASC]);
            } else {
                foreach ($result as $key => $item) {
                    if ($item['pembelian'] == 0 && $item['stok'] == 0) {
                        unset($result[$key]);
                    }
                }
            }
        } else {
            if ($jenis == 1) {
                foreach ($result as $key => $item) {
                    if ($item['pembelian'] == 1) {
                        unset($result[$key]);
                    }
                }
            } elseif ($jenis == 2) {
                foreach ($result as $key => $item) {
                    if ($item['stok'] == 1) {
                        unset($result[$key]);
                    }
                }
            } else {
                foreach ($result as $key => $item) {
                    if ($item['pembelian'] == 1 && $item['stok'] == 1) {
                        unset($result[$key]);
                    }
                }
            }
        }

        return $result;
    }

    public function getDataProyeksi($params)
    {
        $query = DataProyeksi::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $bulanAwal = 1;
        $bulanAkhir = 12;
        $tahunAwal = date('Y');
        $tahunAkhir = date('Y');
        $result = [];
        $tanggal = new \DateTime();
        $tanggal->setDate($tanggal->format('Y'), 1, 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01'] = 0;
            $tanggal->modify('+1 month');
        }

        $query->where("(tahun,bulan) >= (" . $tahunAwal . "," . $bulanAwal . ") AND (tahun,bulan) <= (" . $tahunAkhir . "," . $bulanAkhir . ") ");
        $query->andFilterWhere(['>=', 'tahun', $tahunAwal]);
        $query->andFilterWhere(['<=', 'tahun', $tahunAkhir]);

        foreach ($dataProvider->getModels() as $item) {
            $value = $item->konversiVolume14;
            $result[$item->tahun . '-' . $item->bulan . '-01'] += $value;
        }
        return $result;
    }

    public function getDataPenggunaan($params)
    {
        $query = new DataPenggunaan();
        $tahunAwal = date('Y', strtotime('-12 months'));
        $bulanAwal = (int) date('m', strtotime('-12 months'));

        $tahunAkhir = date('Y', strtotime('-1 months'));
        $bulanAkhir = (int) date('m', strtotime('-1 months'));

        $query->bulan_awal = $bulanAwal;
        $query->tahun_awal = $tahunAwal;
        $query->bulan_akhir = $bulanAkhir;
        $query->tahun_akhir = $tahunAkhir;

        $dataProvider = $query->getData($params);


        $result = [];

        $tanggal = new \DateTime();
        $tanggal->modify('-12 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);
        for ($i = 0; $i < 12; $i++) {
            $tahun = $tanggal->format('Y');
            $bulan = (int) $tanggal->format('m');
            $result[$tahun . '-' . $bulan . '-01']['tanggal'] = $tahun . '-' . $bulan . '-01';
            $result[$tahun . '-' . $bulan . '-01']['penggunaan'] = 0;
            $tanggal->modify('+1 month');
        }

        foreach ($dataProvider as $item) {
            $value = $item['penggunaan'];
            $result[$item['tahun'] . '-' . $item['bulan'] . '-01']['penggunaan'] += $value;
        }
        //echo json_encode($result);
        return $result;
    }
}
