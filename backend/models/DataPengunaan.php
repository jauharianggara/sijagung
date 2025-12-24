<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

class DataPenggunaan extends Model
{
    public $tahun;
    public $bulan;
    public $id_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;

    public $kota_kabupaten_plant;


    public $tahun_awal, $tahun_akhir, $bulan_awal, $bulan_akhir;
    public function rules()
    {
        return [
            [['id_plant','provinsi_plant','provinsi_gabungan_plant','pulau_plant'], 'integer'],
            [['tahun_awal', 'tahun_akhir', 'bulan_awal', 'bulan_akhir'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_plant' => 'Plant',
        ];
    }

    public function getData($params)
    {
        $this->load($params);

        $tanggalAwal = new \DateTime();
        $tanggalAwal->setDate($this->tahun_awal, $this->bulan_awal, 1);
        $tanggalAwal->modify('-1 month');

        $this->tahun_awal = $tanggalAwal->format('Y');
        $this->bulan_awal = $tanggalAwal->format('m');

        $tanggalAkhir = new \DateTime();
        $tanggalAkhir->setDate($this->tahun_akhir, $this->bulan_akhir, 1);

        $queryStok = InputDataStok::find();
        $queryStok->joinWith(['plant','plant.provinsi','plant.provinsi.provinsiGabungan','plant.provinsi.pulau']);
        $queryStok->where("(tahun,bulan) >= (" . $this->tahun_awal . "," . $this->bulan_awal . ") AND (tahun,bulan) <= (" . $this->tahun_akhir . "," . $this->bulan_akhir . ") ");
        $queryStok->andFilterWhere([
            'id_plant' => $this->id_plant
        ]);
        $queryStok->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $queryStok->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);
        $queryStok->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $queryStok->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $dataProviderStok = new ActiveDataProvider([
            'query' => $queryStok,
            'pagination' => false,
        ]);

        $queryPembelian = InputDataPembelian::find();
        $queryPembelian->joinWith(['plant','plant.provinsi','plant.provinsi.provinsiGabungan','plant.provinsi.pulau']);
        $queryPembelian->where("(tahun,bulan) >= (" . $this->tahun_awal . "," . $this->bulan_awal . ") AND (tahun,bulan) <= (" . $this->tahun_akhir . "," . $this->bulan_akhir . ") ");
        $queryPembelian->andFilterWhere([
            'id_plant' => $this->id_plant
        ]);
        $queryPembelian->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $queryPembelian->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);
        $queryPembelian->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $queryPembelian->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);
        $dataProviderPembelian = new ActiveDataProvider([
            'query' => $queryPembelian,
            'pagination' => false,
        ]);

        $queryPlant = ProfilPlant::find()
            ->orderBy([
                'nama_plant' => SORT_ASC
            ]);
        $queryPlant->andFilterWhere(['=', 'id', $this->id_plant]);

        $dataProviderPlant = new ActiveDataProvider([
            'query' => $queryPlant,
            'pagination' => false,
        ]);

        $tanggalAwal->modify('1 month');
        $tanggalAkhir->modify('1 month');
        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($tanggalAwal, $interval, $tanggalAkhir);

        $dataPengunaan = [];
        foreach ($dataProviderPlant->getModels() as $plant) {
            $dataPengunaan[$plant->id_user]['nama'] = $plant->nama_plant;
            $dataPengunaan[$plant->id_user]['id'] = $plant->id;
            $dataPengunaan[$plant->id_user]['id_user'] = $plant->id_user;
            $dataPengunaan[$plant->id_user]['id_provinsi'] = $plant->ip_provinsi;
            $dataPengunaan[$plant->id_user]['provinsi'] = $plant->provinsi->nama;
            $dataPengunaan[$plant->id_user]['id_provinsi_gabungan'] = $plant->provinsi->provinsiGabungan->id;
            $dataPengunaan[$plant->id_user]['provinsi_gabungan'] = $plant->provinsi->provinsiGabungan->nama;
            $dataPengunaan[$plant->id_user]['id_pulau'] = $plant->provinsi->pulau->id;
            $dataPengunaan[$plant->id_user]['pulau'] = $plant->provinsi->pulau->nama;

            foreach ($period as $dt) {
                $dataPengunaan[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['pembelian'] = 0;
                $dataPengunaan[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['stok'] = 0;
            }
        }
        foreach ($dataProviderStok->getModels() as $stok) {
            $dataPengunaan[$stok->id_plant]['data'][$stok->tahun][$stok->bulan]['stok'] = $stok->jumlah_stok;
        }

        foreach ($dataProviderPembelian->getModels() as $pembelian) {
            $dataPengunaan[$pembelian->id_plant]['data'][$pembelian->tahun][$pembelian->bulan]['pembelian'] = $pembelian->konversiVolume14;
        }

        $result = [];
        
        foreach ($dataPengunaan as $key => $penggunaan) {
            $id_plant = $penggunaan['id'];
            $nama = $penggunaan['nama'];
            $id_provinsi = $penggunaan['id_provinsi'];
            $provinsi = $penggunaan['provinsi'];
            $id_provinsi_gabungan = $penggunaan['id_provinsi_gabungan'];
            $provinsi_gabungan = $penggunaan['provinsi_gabungan'];
            $id_pulau = $penggunaan['id_pulau'];
            $pulau = $penggunaan['pulau'];
            $id_user = $penggunaan['id_user'];

            foreach ($period as $dt) {
                $tahun = $dt->format('Y');
                $bulan = (int) $dt->format('m');

                $tanggalSebelumnya = $dt->modify('-1 month');
                $tahunSebelumnya = $tanggalSebelumnya->format('Y');
                $bulanSebelumnya = (int) $tanggalSebelumnya->format('m');

                $stokBulanLalu = $dataPengunaan[$id_plant]['data'][$tahunSebelumnya][$bulanSebelumnya]['pembelian'];
                $volumePembelian = $dataPengunaan[$id_plant]['data'][$tahun][$bulan]['pembelian'];
                $stok = $dataPengunaan[$id_plant]['data'][$tahun][$bulan]['pembelian'];

                $stokBulanLalu =  $stokBulanLalu? $stokBulanLalu:0;
                $volumePembelian =  $volumePembelian? $volumePembelian:0;
                $stok =  $stok? $stok:0;
                //$result[$id_plant]['data'][$tahun][$bulan]['penggunaan'] = $stokBulanLalu + $volumePembelian - $stok;
                $result[$id_plant.$tahun.$bulan]['id'] = $id_plant;
                $result[$id_plant.$tahun.$bulan]['nama_plant'] = $nama;
                $result[$id_plant.$tahun.$bulan]['id_provinsi_plant'] = $id_provinsi;
                $result[$id_plant.$tahun.$bulan]['provinsi_plant'] = $provinsi;
                $result[$id_plant.$tahun.$bulan]['id_provinsi_gabungan'] = $id_provinsi_gabungan;
                $result[$id_plant.$tahun.$bulan]['provinsi_gabungan_plant'] = $provinsi_gabungan;
                $result[$id_plant.$tahun.$bulan]['id_pulau'] = $id_pulau;
                $result[$id_plant.$tahun.$bulan]['pulau_plant'] = $pulau;
                $result[$id_plant.$tahun.$bulan]['id_user'] = $id_user;
                $result[$id_plant.$tahun.$bulan]['tahun'] = $tahun;
                $result[$id_plant.$tahun.$bulan]['bulan'] = $bulan;
                $result[$id_plant.$tahun.$bulan]['bulan_label'] = $this->getBulanLabel($bulan);
                $result[$id_plant.$tahun.$bulan]['stokBulanLalu'] = $stokBulanLalu;
                $result[$id_plant.$tahun.$bulan]['stok'] = $stok;
                $result[$id_plant.$tahun.$bulan]['volumePembelian'] = $volumePembelian;
                $result[$id_plant.$tahun.$bulan]['penggunaan'] = $stokBulanLalu + $volumePembelian - $stok;
            }
        }
        return $result;
    }

    public function getBulanLabel($bulan)
    {
        $label = array(
            "0" => "",
            "1" => "Januari",
            "2" => "Februari",
            "3" => "Maret",
            "4" => "April",
            "5" => "Mei",
            "6" => "Juni",
            "7" => "Juli",
            "8" => "Agustus",
            "9" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember",
        );
        return $label[$bulan];
    }
}
?>