<div class="container">
    <h3>Data Mahasiswa</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div style="margin: 1rem">
            <a class="btn" href="<?php echo site_url('admin/mahasiswa/add'); ?>"><i class="fa fa-plus left"></i> TAMBAH MAHASISWA</a>
            <a class="btn" href="<?php echo site_url('admin/mahasiswa/import'); ?>"><i class="fa fa-file-import left"></i> IMPORT MAHASISWA</a>
            <a class="btn red" href="<?php echo site_url('admin/mahasiswa/reset'); ?>" onclick="return confirm('Apakah anda yakin?') && (prompt('Seluruh data mahasiswa akan terhapus dan anda harus melakukan import ulang lagi dengan username SSO yang belum terisi semua. Ketik `saya mengetahui hal tersebut` apabila ingin dilanjut') === 'saya mengetahui hal tersebut')"><i class="fa fa-redo left"></i> RESET MAHASISWA</a>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-mahasiswa" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
            <div style="padding: 1rem">
                <div id="table-buttons"></div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var table = $('#data-mahasiswa').DataTable({
        aLengthMenu: [10, 25, 50, 100, 250, 500, 1000],
        serverSide: true,
        ajax: 'fetch',
        scrollX: true,
        ordering: false,
        searchDelay: 1000,
        columns: [
            {name: 'nim', data: 'nim', title: 'NIM'},
            {name: 'nama', data: 'nama', title: 'Nama'},
            {name: 'prodi', data: 'prodi', title: 'Prodi'},
            {name: 'angkatan', data: 'angkatan', title: 'Angkatan'},
            {name: 'sso_aktif', data: 'sso_aktif', title: 'SSO Aktif'},
            {name: 'sudah_memilih', data: 'sudah_memilih', title: 'Sudah Memilih'},
            {name: 'tindakan', data: 'tindakan', title: 'Tindakan'}
        ],
        dom: 'lftirpB',
        buttons: ['csv']
    });
});
</script>
