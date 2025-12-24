<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;

class DataRasioPenggunaan extends Model
{
    public $tahun;
    public $bulan;
    public $id_plant;
    public $id_user;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;

    public $kota_kabupaten_plant;


    public $tahun_awal, $tahun_akhir, $bulan_awal, $bulan_akhir;
    public function rules()
    {
        return [
            [['id_plant','provinsi_plant','provinsi_gabungan_plant','pulau_plant','id_user'], 'integer'],
            [['tahun_awal', 'tahun_akhir', 'bulan_awal', 'bulan_akhir'], 'integer'],
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
            'id_user' => $this->id_user
        ]);
        $queryStok->andFilterWhere(['>=', 'tahun', $this->tahun_awal]);
        $queryStok->andFilterWhere(['<=', 'tahun', $this->tahun_akhir]);
        $queryStok->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $queryStok->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $dataProviderStok = new ActiveDataProvider([
            'query' => $queryStok,
            'pagination' => false,
        ]);

        $queryPenggunaan = new DataPenggunaan();
        $queryPenggunaan->bulan_awal = $this->bulan_awal;
        $queryPenggunaan->tahun_awal = $this->tahun_awal;
        $queryPenggunaan->bulan_akhir = $this->bulan_akhir;
        $queryPenggunaan->tahun_akhir = $this->tahun_akhir;
        $dataProviderPenggunaan = $queryPenggunaan->getData($params);

        $queryPlant = ProfilPlant::find()
            ->orderBy([
                'nama_plant' => SORT_ASC
            ]);
        $queryPlant->andFilterWhere(['=', 'id_user', $this->id_user]);

        $dataProviderPlant = new ActiveDataProvider([
            'query' => $queryPlant,
            'pagination' => false,
        ]);

        $tanggalAwal->modify('1 month');
        $tanggalAkhir->modify('1 month');
        $interval = new \DateInterval('P1M');
        $period = new \DatePeriod($tanggalAwal, $interval, $tanggalAkhir);

        $dataRasioPengunaan = [];
        foreach ($dataProviderPlant->getModels() as $plant) {
            $dataRasioPengunaan[$plant->id_user]['nama'] = $plant->nama_plant;
            $dataRasioPengunaan[$plant->id_user]['id'] = $plant->id;
            $dataRasioPengunaan[$plant->id_user]['id_user'] = $plant->id_user;
            $dataRasioPengunaan[$plant->id_user]['id_provinsi'] = $plant->ip_provinsi;
            $dataRasioPengunaan[$plant->id_user]['provinsi'] = $plant->provinsi->nama;
            $dataRasioPengunaan[$plant->id_user]['id_provinsi_gabungan'] = $plant->provinsi->provinsiGabungan->id;
            $dataRasioPengunaan[$plant->id_user]['provinsi_gabungan'] = $plant->provinsi->provinsiGabungan->nama;
            $dataRasioPengunaan[$plant->id_user]['id_pulau'] = $plant->provinsi->pulau->id;
            $dataRasioPengunaan[$plant->id_user]['pulau'] = $plant->provinsi->pulau->nama;

            foreach ($period as $dt) {
                $dataRasioPengunaan[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['stok'] = 0;
                $dataRasioPengunaan[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['penggunaan'] = 0;
                $dataRasioPengunaan[$plant->id_user]['data'][$dt->format('Y')][$dt->format('n')]['rasioPenggunaan'] = 0;
            }
        }
        foreach ($dataProviderStok->getModels() as $stok) {
            $dataRasioPengunaan[$stok->id_plant]['data'][$stok->tahun][$stok->bulan]['stok'] = $stok->jumlah_stok;
        }

        foreach ($dataProviderPenggunaan as $penggunaan) {
            $dataRasioPengunaan[$penggunaan['id_user'].$penggunaan['tahun'].$penggunaan['bulan']]['data'][$penggunaan['tahun']][$penggunaan['bulan']]['penggunaan'] = $penggunaan['penggunaan'];
        }

        $result = [];
        
        foreach ($dataRasioPengunaan as $key => $rasioPenggunaan) {
            $id_plant = $rasioPenggunaan['id'];
            $nama = $rasioPenggunaan['nama'];
            $id_provinsi = $rasioPenggunaan['id_provinsi'];
            $provinsi = $rasioPenggunaan['provinsi'];
            $id_provinsi_gabungan = $rasioPenggunaan['id_provinsi_gabungan'];
            $provinsi_gabungan = $rasioPenggunaan['provinsi_gabungan'];
            $id_pulau = $rasioPenggunaan['id_pulau'];
            $pulau = $rasioPenggunaan['pulau'];
            $id_user = $rasioPenggunaan['id_user'];

            if($id_plant){
                foreach ($period as $dt) {
                    $tahun = $dt->format('Y');
                    $bulan = (int) $dt->format('m');
    
                    $stok = $dataRasioPengunaan[$id_user]['data'][$tahun][$bulan]['stok'];
                    $penggunaan =  $dataRasioPengunaan[$id_user.$tahun.$bulan]['data'][$tahun][$bulan]['penggunaan'];
    
                    $stok =  $stok? $stok/1000:0;
                    $penggunaan =  $penggunaan? $penggunaan/1000:0;
    
                    //$result[$id_plant]['data'][$tahun][$bulan]['penggunaan'] = $stokBulanLalu + $volumePembelian - $stok;
                    $result[$id_user.''.$tahun.''.$bulan]['id'] = $id_plant;
                    $result[$id_user.''.$tahun.''.$bulan]['nama_plant'] = $nama;
                    $result[$id_user.''.$tahun.''.$bulan]['id_provinsi_plant'] = $id_provinsi;
                    $result[$id_user.''.$tahun.''.$bulan]['provinsi_plant'] = $provinsi;
                    $result[$id_user.''.$tahun.''.$bulan]['id_provinsi_gabungan'] = $id_provinsi_gabungan;
                    $result[$id_user.''.$tahun.''.$bulan]['provinsi_gabungan_plant'] = $provinsi_gabungan;
                    $result[$id_user.''.$tahun.''.$bulan]['id_pulau'] = $id_pulau;
                    $result[$id_user.''.$tahun.''.$bulan]['pulau_plant'] = $pulau;
                    $result[$id_user.''.$tahun.''.$bulan]['id_user'] = $id_user;
                    $result[$id_user.''.$tahun.''.$bulan]['tahun'] = $tahun;
                    $result[$id_user.''.$tahun.''.$bulan]['bulan'] = $bulan;
                    $result[$id_user.''.$tahun.''.$bulan]['bulan_label'] = $this->getBulanLabel($bulan);
                    $result[$id_user.''.$tahun.''.$bulan]['stok'] = $stok;
                    $result[$id_user.''.$tahun.''.$bulan]['penggunaan'] = $penggunaan;
                    $rasioPenggunaan = 0;
                    if($stok > 0 && $penggunaan >0){
                        $rasioPenggunaan = ($stok/$penggunaan)*100;
                    }
                    $result[$id_user.''.$tahun.''.$bulan]['rasioPenggunaan'] = $rasioPenggunaan;

                    //
                }
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