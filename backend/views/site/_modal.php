<?php
    $this->registerJs(<<<JS
    $(document).ready(function(){
        $('#verticalcenter').modal('show')
        })
    ;
JS
);
?>
<!-- sample modal content -->
<div id="verticalcenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="vcenter">Informasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Penginputan data pembelian dan stok dilakukan maksimal tanggal 10 setiap bulan jam 13.00 WIB</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->