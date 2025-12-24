<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProfilSupplier;

/**
 * ProfilSupplierSearch represents the model behind the search form of `backend\models\ProfilSupplier`.
 */
class ProfilSupplierSearch extends ProfilSupplier
{
    public $bulan;
    public $tahun;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_plant', 'userAdd', 'userEdit'], 'integer'],
            [['nama', 'alamat', 'id_provinsi', 'id_kota_kabupaten', 'id_kecamatan', 'id_kelurahan', 'dateAdd', 'dateEdit','bulan','tahun','telepon'], 'safe'],
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
        $query = ProfilSupplier::find();
        $query->select(['profil_plant.nama_plant AS nama_plant','profil_supplier.*']);
        $query->joinWith([
          'plant','plant.plant','inputDataPembelianSuppliers','inputDataPembelianSuppliers.inputDataPembelian'
        ]);

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
            'profil_supplier.id' => $this->id,
            'profil_supplier.id_plant' => $this->id_plant,
            'profil_supplier.userAdd' => $this->userAdd,
            'profil_supplier.dateAdd' => $this->dateAdd,
            'profil_supplier.userEdit' => $this->userEdit,
            'profil_supplier.dateEdit' => $this->dateEdit,
        ]);

        $query->andFilterWhere(['like', 'profil_supplier.nama', $this->nama])
            ->andFilterWhere(['like', 'profil_supplier.alamat', $this->alamat])
            ->andFilterWhere(['like', 'profil_supplier.telepon', $this->telepon])
            ->andFilterWhere(['like', 'profil_supplier.id_provinsi', $this->id_provinsi])
            ->andFilterWhere(['like', 'profil_supplier.id_kota_kabupaten', $this->id_kota_kabupaten])
            ->andFilterWhere(['like', 'profil_supplier.id_kecamatan', $this->id_kecamatan])
            ->andFilterWhere(['like', 'profil_supplier.id_kelurahan', $this->id_kelurahan]);

            $query->andFilterWhere([
                'input_data_pembelian.bulan' => $this->bulan,
                'input_data_pembelian.tahun'=>$this->tahun,
            ]);
        return $dataProvider;
    }
}
