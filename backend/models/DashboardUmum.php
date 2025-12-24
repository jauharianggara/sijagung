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
class DashboardUmum extends Model
{
    public function getDataVolumeTahunIni($params)
    {
        $query = InputDataPembelian::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-13 month');
        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $result = [];
        $query->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('m') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('m') . ") ");
        $query->andWhere(['>=','tahun',2023]);


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

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-13 month');
        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $result = [];
        $query->where("(tahun,bulan) >= (" . $tanggalAwal->format('Y') . "," . $tanggalAwal->format('m') . ") AND (tahun,bulan) <= (" . $tanggalAkhir->format('Y') . "," . $tanggalAkhir->format('m') . ") ");
        $query->andWhere(['>=','tahun',2023]);

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


        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-14 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-14 month');
        $bulanAwal = (int) $tanggalAwal->format('m');
        $tahunAwal = $tanggalAwal->format('Y');

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $bulanAkhir = (int) $tanggalAkhir->format('m');
        $tahunAkhir = $tanggalAkhir->format('Y');


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
        $tanggal = new \DateTime();
        $tanggal->modify('-14 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-14 month');
        $bulanAwal = (int) $tanggalAwal->format('m');
        $tahunAwal = $tanggalAwal->format('Y');

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $bulanAkhir = (int) $tanggalAkhir->format('m');
        $tahunAkhir = $tanggalAkhir->format('Y');

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


        $result = [];
        $tanggal = new \DateTime();
        $tanggal->modify('-14 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-14 month');
        $bulanAwal = (int) $tanggalAwal->format('m');
        $tahunAwal = $tanggalAwal->format('Y');

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $bulanAkhir = (int) $tanggalAkhir->format('m');
        $tahunAkhir = $tanggalAkhir->format('Y');

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

        $tanggal = new \DateTime();
        $tanggal->modify('-14 month');
        $tanggal->setDate($tanggal->format('Y'), $tanggal->format('m'), 1);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->modify('-14 month');
        $bulanAwal = (int) $tanggalAwal->format('m');
        $tahunAwal = $tanggalAwal->format('Y');

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->modify('-3 month');
        $bulanAkhir = (int) $tanggalAkhir->format('m');
        $tahunAkhir = $tanggalAkhir->format('Y');

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
            if ($item->jumlah_stok > 0) {
                $result[$item->tahun . '-' . $item->bulan . '-01']['value'] += $value;
                $result[$item->tahun . '-' . $item->bulan . '-01']['count']++;
                $result[$item->tahun . '-' . $item->bulan . '-01']['average'] += $value;
            }
        }

        foreach ($result as $item) {
            $average = 0;
            if ($item['count'] > 0) {
                $average = $item['value'] / $item['count'];
            }
            $result[$item['tanggal']]['average'] = round($average, 0, PHP_ROUND_HALF_UP);
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
}