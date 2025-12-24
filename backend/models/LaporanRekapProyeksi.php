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
class LaporanRekapProyeksi extends DataProyeksi
{
    public $wilayah;
    public $nama_plant;
    public $provinsi_plant;
    public $provinsi_gabungan_plant;
    public $pulau_plant;
    public $kota_kabupaten_plant;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'string', 'max' => 255],
            [['id', 'tahun', 'wilayah', 'provinsi_gabungan_plant', 'pulau_plant'], 'integer'],
            [['bulan', 'nama_plant', 'provinsi_plant', 'kota_kabupaten_plant'], 'safe'],
            [['tahun', 'wilayah'], 'required'],

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
        //$query->joinWith(['plant']);
        $query->joinWith(['plant', 'plant.plant.provinsi', 'plant.plant.provinsi.provinsiGabungan', 'plant.plant.provinsi.pulau']);

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
        $query->andFilterWhere(['>', 'volume', 0]);
        $query->andFilterWhere(['>', 'kadar_air_persen', 0]);
        
        if ($this->wilayah == 1) {
            // nasional
            $pulau = Pulau::find()->orderBy(['id'=>SORT_ASC])->all();
            $result_temp = [];
            foreach ($pulau as $item) {
                $result_temp[$item->id] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                ];
                for ($i = 1; $i <= 12; $i++) {
                    $result_temp[$item->id]['bulan'][$i]['bulan'] = $i;
                    $result_temp[$item->id]['bulan'][$i]['volume'] = 0;
                }
            }
        } elseif ($this->wilayah == 2) {
            // provinsi
            $provinsi = Provinsi::find()->orderBy(['id'=>SORT_ASC])->all();
            $result_temp = [];
            foreach ($provinsi as $item) {
                $result_temp[$item->id] = [
                    'id' => $item->id,
                    'nama' => $item->nama,
                ];
                for ($i = 1; $i <= 12; $i++) {
                    $result_temp[$item->id]['bulan'][$i]['bulan'] = $i;
                    $result_temp[$item->id]['bulan'][$i]['volume'] = 0;
                }
            }
        } elseif ($this->wilayah == 3) {
            // provinsi
            $provinsiGabungan = ProvinsiGabungan::find()->orderBy(['id' => SORT_ASC])->all();
            $result_temp = [];
            foreach ($provinsiGabungan as $item) {
                $result_temp[$item->id] = [
                    'id' => $item->id,
                    'nama' => $item->nama,
                ];
                for ($i = 1; $i <= 12; $i++) {
                    $result_temp[$item->id]['bulan'][$i]['bulan'] = $i;
                    $result_temp[$item->id]['bulan'][$i]['volume'] = 0;
                }
            }
        } else {
            // plant
            // provinsi
            $plant = ProfilPlant::find()->orderBy(['nama'=>SORT_ASC])->all();
            $result_temp = [];
            foreach ($plant as $item) {
                $result_temp[$item->id_user] = [
                    'id' => $item->id_user,
                    'nama' => ucwords(strtolower($item->nama_plant)),
                ];
                for ($i = 1; $i <= 12; $i++) {
                    $result_temp[$item->id_user]['bulan'][$i]['bulan'] = $i;
                    $result_temp[$item->id_user]['bulan'][$i]['volume'] = 0;
                }
            }
        }

        foreach ($dataProvider->getModels() as $item) {
            $volume = $item->konversiVolume14;
            if ($this->wilayah == 1) {
                // nasional
                $id = $item->plant->plant->provinsi->id_pulau;
            } elseif ($this->wilayah == 2) {
                // provinsi
                $id = $item->plant->plant->ip_provinsi;
            } elseif ($this->wilayah == 3) {
                // provinsi
                $id = $item->plant->plant->provinsi->id_provinsi_gabungan;
            } else {
                //plant
                $id = $item->id_plant;
            }
            $result_temp[$id]['bulan'][$item->bulan]['volume'] += $volume;
        }

        $result = [];
        if ($this->wilayah == 1 || $this->wilayah == 3) {
            // nasional atau plant
            foreach ($result_temp as $items) {
                $result[$items['id']] = [
                    'id' => $items['id'],
                    'nama' => $items['nama'],
                ];
                for ($i = 1; $i <= 12; $i++) {
                    $result[$items['id']]['bulan'][$i]['bulan'] = $i;
                }
                foreach ($items['bulan'] as $item) {
                    $result[$items['id']]['bulan'][$item['bulan']]['volume'] = $item['volume'];
                }
            }
        } elseif ($this->wilayah == 2) {
            // provinsi
            foreach ($result_temp as $items) {
                $result[$items['id']] = [
                    'id' => $items['id'],
                    'nama' => $items['nama'],
                ];
                foreach ($items['bulan'] as $item) {
                    //$result[$items['id']]['bulan'][$item['bulan']]['volume'] += $item['volume'];
                    $result[$items['id']]['bulan'][$item['bulan']]['volume'] = $item['volume'];

                }
            }
        }

        //ArrayHelper::multisort($result, ['nama'], [SORT_ASC]);
        return $result;

    }
}
