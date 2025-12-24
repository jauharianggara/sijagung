<?php
use yii\helpers\ArrayHelper;
use backend\models\ProfilPlant;
use backend\models\Provinsi;

$tahunAwal = date("Y") + 1;
$tahunAkhir = 2023;
?>
<div class="row">
    <div class="col-6">
        <div class="row">
            <div class="col-12">
                <?php
                for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
                    $tahun[$i] = $i;
                }
                echo $form->field($model, 'tahun_awal')->dropDownList(
                    $tahun,
                    [
                        'id' => 'tahun',
                        'prompt' => '-- Tahun --',
                    ]
                )->label('Tahun Awal');
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $form->field($model, 'bulan_awal')->dropDownList(
                    [
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
                    ],
                    [
                        'id' => 'bulan_awal',
                        'prompt' => '-- Pilih Bulan --',
                    ]
                )->label('Bulan Awal');
                ?>
            </div>
            <div class="col-12">
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-12">
                <?php
                for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
                    $tahun[$i] = $i;
                }
                echo $form->field($model, 'tahun_akhir')->dropDownList(
                    $tahun,
                    [
                        'id' => 'tahun',
                        'prompt' => '-- Tahun --',
                    ]
                )->label('Tahun Akhir');
                ?>
            </div>
            <div class="col-12">
                <?php
                echo $form->field($model, 'bulan_akhir')->dropDownList(
                    [
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
                    ],
                    [
                        'id' => 'bulan_akhir',
                        'prompt' => '-- Pilih Bulan --',
                    ]
                )->label('Bulan Akhir');
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?php
        if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
            $dataProvinsi = ArrayHelper::map(Provinsi::find()->orderBy(['nama' => 'ASC'])->asArray()->all(), 'id', 'nama');
            echo $form->field($model, 'provinsi_plant')->dropDownList(
                $dataProvinsi,
                [
                    'id' => 'id_provinsi',
                    'prompt' => '-- Provinsi --',
                    'onchange' => '
                    $.post( "' . Yii::$app->urlManager->createUrl('profil-plant/list?id=') . '"+$(this).val(), function( data ) {
                    $( "select#id_plant" ).html( data );
                    });
                ',
                ]
            );
        }
        ?>
    </div>
    <div class="col-12">
        <?php
        if (\Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)) {
            $dataPlant = ArrayHelper::map(ProfilPlant::find()->orderBy(['nama_plant' => 'ASC'])->asArray()->all(), 'id', 'nama_plant');
            echo $form->field($model, 'nama_plant')->dropDownList(
                $dataPlant,
                [
                    'id' => 'id_plant',
                    'prompt' => '-- Plant --',
                ]
            );
        }
        ?>
    </div>
</div>
</div>