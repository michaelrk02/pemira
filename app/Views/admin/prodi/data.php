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
<div id="sesiModal" class="modal">
    <div class="modal-content">
        <h4>Manage Jadwal</h4>
        <div>
            <h6 id="manageSesi_title"></h6>
            <div>
                <label>ID Prodi:</label>
                <input id="manageSesi_idProdi" type="number" readonly class="browser-default">
            </div>
            <div>
                <label>Jadwal:</label>
                <select id="manageSesi_listSesi" class="browser-default">
                    <option value="" disabled selected>Pilih sesi</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn green" id="manageSesi_kirim"><i class="fa fa-paper-plane left"></i> KIRIM</button>
        <button type="button" class="btn red" onclick="sesiModal.close()"><i class="fa fa-times left"></i> TUTUP</button>
    </div>
</div>
<script>

var sesiModal;

function manageSesi(action, idProdi) {
    var url;
    $('#manageSesi_idProdi').val(idProdi);
    $('#manageSesi_listSesi').val('');
    if (action === 'add') {
        url = 'addsesi';
        $('#manageSesi_title').text('Tambah Jadwal');
    } else if (action === 'del') {
        url = 'delsesi';
        $('#manageSesi_title').text('Hapus Jadwal');
    }

    var reqData = null;
    if (action === 'del') {
        reqData = {id: idProdi};
    }
    $.ajax({
        url: 'listsesi',
        data: reqData,
        success: function(data) {
            $('#manageSesi_listSesi option.sesiOption').remove();

            var elements = [];
            data.forEach(function(element) {
                if (action === 'add') {
                    elements.push('<option value="' + element.ID + '" class="sesiOption">' + _.escape('(' + element.ID + ') ' + element.Nama) + '</option>');
                } else if (action === 'del') {
                    elements.push('<option value="' + element.sesi_id + '" class="sesiOption">' + _.escape('(' + element.sesi_id + ') ' + element.sesi_nama) + '</option>');
                }
            });
            elements = elements.join('');
            $('#manageSesi_listSesi').append(elements);

            $('#manageSesi_kirim').off('click').on('click', function() {
                if (confirm('Apakah anda yakin?')) {
                    $.ajax({
                        url: url,
                        data: {idprodi: $('#manageSesi_idProdi').val(), idsesi: $('#manageSesi_listSesi').val()},
                        success: function() {
                            if (action === 'add') {
                                showToast('Berhasil menambah sesi', 'green white-text');
                            } else if (action === 'del') {
                                showToast('Berhasil menghapus sesi', 'green white-text');
                            }
                            sesiModal.close();
                        },
                        error: function(xhr) {
                            showToast(xhr.statusText, 'red white-text');
                        }
                    });
                }
            });

            sesiModal.open();
        },
        error: function(xhr) {
            showToast(xhr.statusText, 'red white-text');
        }
    });
}

$(document).ready(function() {
    $('.modal').modal();

    sesiModal = M.Modal.getInstance(document.querySelector('#sesiModal'));

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
        createdRow: function(row) {
            $(row).find('.btn.listSesi').off('click').on('click', function(e) {
                $.ajax({
                    url: 'listsesi',
                    data: {id: $(e.currentTarget).data('id')},
                    success: function(data) {
                        var listSesi = [];
                        data.forEach(function(sesi) {
                            listSesi.push('<li>' + _.escape(sesi.sesi_nama) + '</li>');
                        });
                        listSesi = listSesi.join('');
                        showToast('<p>Jadwal:</p><ul class="browser-default">' + listSesi + '</ul>');
                    },
                    error: function(xhr) {
                        showToast(xhr.statusText, 'red white-text');
                    }
                });
            });

            $(row).find('.btn.addSesi').off('click').on('click', function(e) {
                manageSesi('add', $(e.currentTarget).data('id'));
            });

            $(row).find('.btn.delSesi').off('click').on('click', function(e) {
                manageSesi('del', $(e.currentTarget).data('id'));
            });
        }
    });
});

</script>
