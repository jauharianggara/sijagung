<div class="row">
    <div class="col-12">
        <?php
        $tahunAwal = date("Y") + 1;
        $tahunAkhir = 2023;
        for ($i = $tahunAwal; $i >= $tahunAkhir; $i--) {
            $tahun[$i] = $i;
        }
        echo $form->field($model, 'tahun')->dropDownList(
            $tahun,
            [
                'id' => 'tahun',
                'prompt' => '-- Tahun --',
            ]
        )->label('Tahun');
        ?>
    </div>
    <div class="col-12">
        <?php
        echo $form->field($model, 'wilayah')->dropDownList(
            [
                "1" => "Nasional",
                "2" => "Provinsi",
                "3" => "Provinsi Gabungan",
                "4" => "Plant",
            ],
            [
                'id' => 'wilayah',
                'prompt' => '-- Pilih Wilayah --',
            ]
        );
        ?>
    </div>
</div>