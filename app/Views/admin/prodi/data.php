<div class="container">
    <h3>Data Prodi</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div style="margin: 1rem">
            <a class="btn" href="<?php echo site_url('admin/prodi/add'); ?>"><i class="fa fa-plus left"></i> TAMBAH PRODI</a>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-prodi" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#data-prodi').DataTable({
        serverSide: true,
        ajax: 'fetch',
        scrollX: true,
        ordering: false,
        searchDelay: 1000,
        columns: [
            {name: 'id', data: 'id', title: 'ID'},
            {name: 'nama', data: 'nama', title: 'Nama'},
            {name: 'tindakan', data: 'tindakan', title: 'Tindakan'}
        ],
        rowCallback: function(row) {
            $(row).find('.btn.listJadwal').off('click').on('click', function(e) {
                $.get('listjadwal', {id: $(e.currentTarget).data('id')}, function(data) {
                    showToast(data);
                });
            });
        }
    });
});
</script>
