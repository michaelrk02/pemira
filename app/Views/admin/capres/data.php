<div class="container">
    <h3>Data Capres</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div style="margin: 1rem">
            <a class="btn" href="<?php echo site_url('admin/capres/add'); ?>"><i class="fa fa-plus left"></i> TAMBAH CAPRES</a>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-capres" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#data-capres').DataTable({
        serverSide: true,
        ajax: 'fetch',
        scrollX: true,
        ordering: false,
        searchDelay: 1000,
        columns: [
            {name: 'id', data: 'id', title: 'ID'},
            {name: 'nama', data: 'nama', title: 'Nama'},
            {name: 'tindakan', data: 'tindakan', title: 'Tindakan'}
        ]
    });
});
</script>
