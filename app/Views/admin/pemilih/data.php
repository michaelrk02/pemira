<div class="container">
    <h3>Data Pemilih</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <form method="get" style="margin: 1rem">
            <div class="input-field">
                <input id="input-idcapres" type="number" name="idcapres" value="<?php echo $idcapres ?? ''; ?>">
                <label for="input-idcapres">ID Capres</label>
            </div>
            <div class="input-field">
                <input id="input-idcaleg" type="number" name="idcaleg" value="<?php echo $idcaleg ?? ''; ?>">
                <label for="input-idcaleg">ID Caleg</label>
            </div>
            <div><button type="submit" class="btn">GO</button></div>
        </form>
        <div style="margin: 1rem">
            <p>Token Secret Hash: <b><?php echo $tokenSecretHash; ?></b> <a class="tooltipped" data-location="top" data-tooltip="Apa maksudnya ini?" href="#!" onclick="showTokenSecretHashUsage(event)"><i class="fa fa-info-circle"></i></a></p>
            <p>Status hasil rekapitulasi: <?php echo $valid ? '<b class="green white-text" style="padding: 4px">DAPAT DIPERCAYA</b>' : '<b class="red white-text" style="padding: 4px">TIDAK DAPAT DIPERCAYA</b>'; ?></p>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-pemilih" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
        </div>
    </div>
</div>
<script>
function showTokenSecretHashUsage(e) {
    e.preventDefault();
    showToast('\'Token Secret Hash\' digunakan untuk mengecek validitas suatu token. Jika ditemukan suatu token yang tidak valid, sangat disarankan untuk menghapus entri tersebut melalui database secara langsung');
}

$(document).ready(function() {
    $('#data-pemilih').DataTable({
        serverSide: true,
        ajax: {
            url: 'fetch',
            data: {
                idcapres: '<?php echo $idcapres ?? ''; ?>',
                idcaleg: '<?php echo $idcaleg ?? ''; ?>'
            }
        },
        scrollX: true,
        ordering: false,
        searching: false,
        columns: [
            {
                name: 'id',
                data: 'token',
                title: '#',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {name: 'token', data: 'token', title: 'Token'},
            {name: 'valid', data: 'valid', title: 'Valid'},
            {name: 'prodi', data: 'prodi', title: 'Prodi'},
            {name: 'idcapres', data: 'idcapres', title: 'ID Capres'},
            {name: 'idcaleg', data: 'idcaleg', title: 'ID Caleg'}
        ]
    });
});
</script>
