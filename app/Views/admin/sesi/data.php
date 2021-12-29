<div class="container">
    <h3>Data Sesi</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div style="margin: 1rem">
            <a class="btn" href="<?php echo site_url('admin/sesi/add'); ?>"><i class="fa fa-plus left"></i> TAMBAH SESI</a>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-sesi" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
        </div>
    </div>
</div>
<div id="jadwalModal" class="modal">
    <div class="modal-content">
        <h4>Manage Jadwal</h4>
        <div>
            <h6 id="manageJadwal_title"></h6>
            <div>
                <label>ID Sesi:</label>
                <input id="manageJadwal_idSesi" type="number" readonly class="browser-default">
            </div>
            <div>
                <label>Nama Sesi:</label>
                <input id="manageJadwal_namaSesi" type="text" readonly class="browser-default">
            </div>
            <div>
                <label>Jadwal:</label>
                <select id="manageJadwal_listProdi" class="browser-default">
                    <option value="" disabled selected>Pilih prodi</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn green" id="manageJadwal_kirim"><i class="fa fa-paper-plane left"></i> KIRIM</button>
        <button type="button" class="btn red" onclick="jadwalModal.close()"><i class="fa fa-times left"></i> TUTUP</button>
    </div>
</div>
<script>

var jadwalModal;

function manageJadwal(action, idSesi) {
    var url;
    $('#manageJadwal_idSesi').val(idSesi);
    $('#manageJadwal_namaSesi').val($('.btn.addJadwal[data-id="' + idSesi + '"]').data('nama'));
    $('#manageJadwal_listProdi').val('');
    if (action === 'add') {
        url = 'addjadwal';
        $('#manageJadwal_title').text('Tambah Jadwal');
    } else if (action === 'del') {
        url = 'deljadwal';
        $('#manageJadwal_title').text('Hapus Jadwal');
    }

    reqData = {id: idSesi, action: action};
    $.ajax({
        url: 'listprodi',
        data: reqData,
        success: function(data) {
            $('#manageJadwal_listProdi option.prodiOption').remove();

            var elements = [];
            data.forEach(function(element) {
                elements.push('<option value="' + element.prodi_id + '" class="prodiOption">' + _.escape('(' + element.prodi_id + ') ' + element.prodi_nama) + '</option>');
            });
            elements = elements.join('');
            $('#manageJadwal_listProdi').append(elements);

            $('#manageJadwal_kirim').off('click').on('click', function() {
                if (confirm('Apakah anda yakin?')) {
                    $.ajax({
                        url: url,
                        data: {idsesi: $('#manageJadwal_idSesi').val(), idprodi: $('#manageJadwal_listProdi').val()},
                        success: function() {
                            if (action === 'add') {
                                showToast('Berhasil menambah jadwal', 'green white-text');
                            } else if (action === 'del') {
                                showToast('Berhasil menghapus jadwal', 'green white-text');
                            }
                            jadwalModal.close();
                        },
                        error: function(xhr) {
                            showToast(xhr.statusText, 'red white-text');
                        }
                    });
                }
            });

            jadwalModal.open();
        },
        error: function(xhr) {
            showToast(xhr.statusText, 'red white-text');
        }
    });
}

$(document).ready(function() {
    $('.modal').modal();

    jadwalModal = M.Modal.getInstance(document.querySelector('#jadwalModal'));

    $('#data-sesi').DataTable({
        serverSide: true,
        ajax: 'fetch',
        scrollX: true,
        ordering: false,
        searchDelay: 1000,
        columns: [
            {name: 'id', data: 'id', title: 'ID'},
            {name: 'nama', data: 'nama', title: 'Nama'},
            {name: 'waktu_buka', data: 'waktu_buka', title: 'Waktu Buka'},
            {name: 'waktu_tutup', data: 'waktu_tutup', title: 'Waktu Tutup'},
            {name: 'tindakan', data: 'tindakan', title: 'Tindakan'}
        ],
        rowCallback: function(row) {
            $(row).find('.btn.listJadwal').off('click').on('click', function(e) {
                $.get('listjadwal', {id: $(e.currentTarget).data('id')}, function(data) {
                    showToast(data);
                });
            });

            $(row).find('.btn.addJadwal').off('click').on('click', function(e) {
                manageJadwal('add', $(e.currentTarget).data('id'));
            });

            $(row).find('.btn.delJadwal').off('click').on('click', function(e) {
                manageJadwal('del', $(e.currentTarget).data('id'));
            });
        }
    });
});

</script>
