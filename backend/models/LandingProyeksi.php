<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use backend\models\DataProyeksi;

/**
 * InputDataPembelianSearch represents the model behind the search form of `backend\models\InputDataPembelian`.
 */
class LandingProyeksi extends DataProyeksi
{
    public $wilayah;
    public $nama_plant;
    public $provinsi_plant;
    public $kota_kabupaten_plant;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id', 'tahun', 'wilayah'], 'integer'],
            [['bulan', 'nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'safe'],
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
        $query = DataProyeksi::find();
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_plant' => $this->id_plant,
            'tahun' => $this->tahun,
        ]);


        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

        $query->andFilterWhere(['=', 'profil_plant.id', $this->nama_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_provinsi', $this->provinsi_plant]);
        $query->andFilterWhere(['=', 'profil_plant.ip_kota', $this->kota_kabupaten_plant]);

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

        foreach ($dataProvider->getModels() as $item) {
            $id_pulau = $item->plant->plant->provinsi->id_pulau;
            $id_provinsi = $item->plant->plant->ip_provinsi;
            $id_provinsi_gabungan = $item->plant->plant->provinsi->id_provinsi_gabungan;
            $id_plant = $item->id_plant;

            $volume = $item->konversiVolume14 / 1000;
            if ($this->wilayah == 1) {
                // nasional
                $result[$id_pulau]['bulan'][$item->bulan]['volume'] += $volume;
            } elseif ($this->wilayah == 2) {
                // provinsi
                $result[$id_pulau]['bulan'][$item->bulan]['volume'] += $volume;
                $result[$id_pulau]['provinsi'][$id_provinsi]['bulan'][$item->bulan]['volume'] += $volume;
            } elseif ($this->wilayah == 3) {
                // provinsi gabungan
                $result[$id_pulau]['bulan'][$item->bulan]['volume'] += $volume;
                $result[$id_pulau]['provinsi'][$id_provinsi_gabungan]['bulan'][$item->bulan]['volume'] += $volume;
            } else {
                // plant
                $result[$id_plant]['bulan'][$item->bulan]['volume'] += $volume;
            }
        }
        /**
        if ($this->wilayah == 2) {
            // nasional
            $result['13']['nama'] = 'Sumatera Barat & Lampung';
            $result['31']['nama'] = 'DKI Jakarta & Banten';
            $result['61']['nama'] = 'Kalimantan Barat & Kalimantan Selatan';
            foreach ($result as $item) {
                if ($item['id'] == '18') {
                    foreach ($item['bulan'] as $bulan => $itemBulan) {
                        $result['13']['bulan'][$bulan]['volume'] += $itemBulan['volume'];
                    }
                } elseif ($item['id'] == '63') {
                    foreach ($item['bulan'] as $bulan => $itemBulan) {
                        $result['61']['bulan'][$bulan]['volume'] += $itemBulan['volume'];
                    }
                } elseif ($item['id'] == '36') {
                    foreach ($item['bulan'] as $bulan => $itemBulan) {
                        $result['31']['bulan'][$bulan]['volume'] += $itemBulan['volume'];
                    }
                }
            }
            unset($result[18]);
            unset($result[63]);
            unset($result[36]);
        }
        **/


        /**
        if($this->wilayah == 1 || $this->wilayah == 3){
          // nasional atau plant
          foreach ($result as $items) {
              $result[$items['id']]['bulan'][$item['bulan']]['volume'] = $item['volume'];
          }
        }elseif($this->wilayah == 2){
          // provinsi
          $result[13]['nama'] ='Sumatera Barat & Lampung';
          $result[31]['nama'] = 'DKI Jakarta & Banten';
          $result[61]['nama'] ='Kalimantan Barat & Kalimantan Selatan';

          foreach ($result as $items) {
            if(  $items['id']== '18'){
              foreach ($items['bulan'] as $item) {
                $result[13]['bulan'][$item['bulan']]['volume'] += $item['volume'];
              }
            }elseif(  $items['id']== '63'){
              foreach ($items['bulan'] as $item) {
                $result[61]['bulan'][$item['bulan']]['volume'] += $item['volume'];
              }
            }elseif( $items['id']== '36'){
              foreach ($items['bulan'] as $item) {
                $result[31]['bulan'][$item['bulan']]['volume'] += $item['volume'];
              }
            }else{
              $result[$items['id']] = [
                'id'=>$items['id'],
                'nama'=>$items['nama'],
              ];
              foreach ($items['bulan'] as $item) {
                //$result[$items['id']]['bulan'][$item['bulan']]['volume'] += $item['volume'];
                $result[$items['id']]['bulan'][$item['bulan']]['volume'] = $item['volume'];
              }
            }
          }
        }
         */
        foreach ($result as $pulau) {
            if (!isset($pulau['bulan'])) {
                unset($result[$pulau['id']]);
            }
            foreach ($pulau['provinsi'] as $provinsi) {
                if (!isset($provinsi['bulan'])) {
                    unset($result[$pulau['id']]['provinsi'][$provinsi['id']]);
                }
            }
        }

        ArrayHelper::multisort($result, ['id'], [SORT_ASC]);
        return $result;

    }
}
