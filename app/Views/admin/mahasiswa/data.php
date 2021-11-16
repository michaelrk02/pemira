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
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#data-mahasiswa').DataTable({
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
            {name: 'tindakan', data: 'tindakan', title: 'Tindakan'}
        ]
    });
});
</script>
