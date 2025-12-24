<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\BootstrapAsset;

use backend\models\User;

setlocale(LC_TIME, 'id_ID.UTF-8');

/* @var $this yii\web\View */
/* @var $model backend\models\DataProyeksi */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(
    "@web/numberFormatter.js",
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);

$this->registerJsFile(
    "@web/plugins/datepicker/bootstrap-datepicker.js",
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);

$this->registerCssFile("@web/plugins/datepicker/datepicker3.css", [
    'depends' => [BootstrapAsset::className()],
]);

$this->registerJs('

	(function($){
	$.fn.datepicker.dates["id"] = {
			days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"],
			daysShort: ["Mgu", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Mgu"],
			daysMin: ["Mg", "Sn", "Sl", "Ra", "Ka", "Ju", "Sa", "Mg"],
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
			today: "Hari Ini",
			clear: "Kosongkan"
		};
	}(jQuery));

	$(".tanggal").datepicker({
		language : \'id\',
		format: \'yyyy-mm-dd\',
		autoclose: true,
		clearBtn:true,
		todayHighlight:true,
		toggleActive:true,
  });
');

$this->registerJs(<<<JS
    $(document).on("change", ".volume", function(){
        hitungPertumbuhan();
    });

    function getTahunSebelumnya(){
        var id_plant = $('#id_plant').val();
        var tahun = $('#tahun').val() - 1;
        $.get( '$urlGetTotalVolume', {id_plant: id_plant,tahun: tahun} )
        .done(function( data ) {
            $('#lbl_total_sebelumnya').val(data);
        });
    }

    function hitungPertumbuhan(){
        var totalSebelumnya = parseInt($('#totalSebelumnya').val(),10);
        var totalTahunIni = 0;
        $(".volume").each(function(){
            var volume = 0;
            if (isNaN($(this).val()) || $(this).val()== null || $(this).val() == ''){
                volume = 0;
            }else{
                volume =parseInt($(this).val(),10);
            }
            //alert(volume);
            totalTahunIni = parseInt(totalTahunIni,10) +  parseInt(volume,10);
        });
        //alert(volume);
        pertumbuhan = ((totalTahunIni - totalSebelumnya)/totalTahunIni)*100;

        $("#total").val(totalTahunIni);
        $("#lbl_total").html(totalTahunIni.format(0, 3, ".", ","));

        $("#pertumbuhan").val(pertumbuhan);

        if(pertumbuhan < 0){
            $("#lbl_pertumbuhan").addClass("text-danger");
        }else{
            $("#lbl_pertumbuhan").removeClass("text-danger");
        }
        $("#lbl_pertumbuhan").html(pertumbuhan.format(0, 3, ".", ",") + '%');


    }
JS
);

?>
<?= Html::input('hidden', 'id_plant', $model->id_plant, $options = ['id' => 'id_plant']) ?>
<?= $form->field($model, 'tahun')->textInput(['readOnly' => 'readOnly', 'id' => 'tahun']) ?>

<div class="table-responsive">
    <table class="table table-bordered" id="data-proyeksi">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Bulan</th>
                <th class="text-center">Volume Kebutuhan (Kg)<br> <span class="text-danger">Masukkan angka volume tanpa
                        tanda pemisah (koma/titik).</span></th>
                <th class="text-center">Kadar Air(%)<br> <span class="text-danger">Masukkan angka kadar air tanpa tanda
                        persen. Gunakan tanda titik sebagai desimal.</span></th>
            </tr>
        </thead>

        <tbody>
            <?php
            $formatter = \Yii::$app->formatter;
            $formatter->locale = 'id-ID';
            $totalProyeksi = 0;
            for ($i = 1; $i <= 12; $i++) {
                $datetime = new DateTime();
                $tanggal = $datetime->createFromFormat('d/m/Y', '1/' . $i . '/22');
                $monthName = $formatter->asDateTime($tanggal, 'php:F');
                $item = $data[$i - 1];
                $totalProyeksi += $item->volume;
                ?>
                <tr>
                    <td>
                        <?= $i ?>.
                    </td>
                    <td>
                        <?= $monthName ?>
                    </td>
                    <td>
                        <?= Html::input('text', 'detail[' . $i . '][volume]', $item->volume, $options = ['class' => 'form-control text-right volume']) ?>
                    </td>
                    <td>
                        <?= Html::input('text', 'detail[' . $i . '][kadar_air_persen]', $item->kadar_air_persen, $options = ['class' => 'form-control text-right']) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>

        <tfoot>
            <tr>
                <td>
                </td>
                <td>
                    Total Proyeksi
                </td>
                <td>
                    <div id="lbl_total" class="text-right">
                        <?php
                        echo $formatter->asInteger($model->total14TahunIni);
                        ?>
                    </div>
                    <?= Html::input('hidden', 'total', $model->total14TahunIni, $options = ['class' => 'form-control text-right', 'disabled' => 'disabled', 'id' => 'total']) ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    Total Proyeksi Tahun Sebelumnya
                </td>
                <td>
                    <div id="lbl_total_sebelumnya" class="text-right">
                        <?php
                        echo $formatter->asInteger($model->total14TahunSebelumnya);
                        ?>
                    </div>
                    <?= Html::input('hidden', 'totalSebelumnya', $model->total14TahunSebelumnya, $options = ['class' => 'form-control text-right', 'disabled' => 'disabled', 'id' => 'totalSebelumnya']) ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    Pertumbuhan
                </td>
                <td>
                    <div id="lbl_pertumbuhan" class="text-right <?= $model->pertumbuhan14 < 0 ? 'text-danger' : '' ?>">
                        <?php
                        echo $formatter->asInteger($model->pertumbuhan14) . '%';
                        ?>
                    </div>
                    <?= Html::input('hidden', 'pertumbuhan', $model->pertumbuhan14, $options = ['class' => 'form-control text-right', 'disabled' => 'disabled']) ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    Kapasitas Produksi Terpasang
                </td>
                <td class="text-right">
                    <?php
                    echo $formatter->asInteger($model->plant->plant->kapasitas_terpasang);
                    ?>
                </td>
                <td>
                    Ton/Tahun
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    Kapasitas Silo
                </td>
                <td class="text-right">
                    <?php
                    echo $formatter->asInteger($model->plant->plant->kapasitas_silo);
                    ?>
                </td>
                <td>
                Ton
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    Kapasitas Dryer
                </td>
                <td class="text-right">
                    <?php
                    echo $formatter->asInteger($model->plant->plant->kapasitas);
                    ?>
                </td>
                <td>
                Ton/Hari
                </td>
            </tr>
        </tfoot>
    </table>
</div>