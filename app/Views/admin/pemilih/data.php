<div class="container">
    <h3>Data Pemilih</h3>
    <div style="margin-top: 4rem; margin-bottom: 4rem">
        <div style="margin: 1rem">
            <div>Pastikan <b>jumlah suara sesungguhnya</b> yang masuk sama dengan jumlah token. Hal ini untuk mengantisipasi adanya token fiktif (terdapat token yang sekilas valid namun tidak merepresentasikan DPT)</div>
        </div>
        <div style="margin: 1rem">
            <button type="button" class="btn" onclick="cekStatistik(this)">CEK STATISTIK</button>
            <a target="_blank" class="btn" href="<?php echo site_url('admin/pemilih/cektokenilegal'); ?>">CEK TOKEN ILEGAL</a>
            <a class="btn red" href="<?php echo site_url('admin/pemilih/reset').'?token='.urlencode($resetToken); ?>" onclick="return confirm('Apakah anda yakin?') && (prompt('Seluruh data pemilih atau suara terhapus dan anda harus melaksanakan pemilihan ulang. Ketik `saya mengetahui hal tersebut` apabila ingin dilanjut') === 'saya mengetahui hal tersebut')"><i class="fa fa-redo left"></i> RESET PEMILIH</a>
        </div>
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
            <p>Status hasil rekapitulasi: <?php echo $normal ? '<b class="green white-text" style="padding: 4px">NORMAL</b>' : '<b class="red white-text" style="padding: 4px">TIDAK NORMAL</b>'; ?></p>
        </div>
        <div class="z-depth-3" style="margin: 1rem">
            <div style="padding: 1rem">
                <table id="data-pemilih" class="striped highlight display nowrap" style="width: 100%"></table>
            </div>
        </div>
        <p>(*) Kolom ini bekerja dengan cara mengecek validitas <a target="_blank" href="https://en.wikipedia.org/wiki/Digital_signature">tanda tangan digital (digital signature)</a> yang terpasang pada setiap token. Dengan demikian, hal ini dapat mengantisipasi adanya kemungkinan manipulasi dari luar. Apabila terdapat banyak token yang tidak valid, sangat disarankan juga untuk dihapus secara manual melalui database dan dapat dilakukan pemilihan ulang lagi (hanya bagi DPT yang tokennya terpengaruh)</p>
    </div>
</div>
<script>
function cekStatistik(self) {
    $(self).attr('disabled', 'disabled');
    $.get('cekstatistik', null, function(data) {
        showToast('<p>Jumlah suara: ' + data.suaraCount + '</p><p>Jumlah token: ' + data.tokenCount + '</p>');
        $(self).attr('disabled', null);
    });
}

function showTokenSecretHashUsage(e) {
    e.preventDefault();
    showToast('\'Token Secret Hash\' digunakan untuk mengecek kenormalan suatu token. Jika ditemukan suatu token yang tidak normal, sangat disarankan untuk menghapus entri tersebut melalui database secara langsung. Hal ini juga menandakan kesiapan internal website dalam hal operasionalnya. Anda sebagai operator umumnya tidak perlu mengkhawatirkan hal ini');
}

$(document).ready(function() {
    $('#data-pemilih').DataTable({
        aLengthMenu: [10, 25, 50, 100, 250, 500, 1000, 5000],
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
            {name: 'normal', data: 'normal', title: 'Normal'},
            {name: 'valid', data: 'valid', title: 'Valid (*)'},
            {name: 'prodi', data: 'prodi', title: 'Prodi'},
            {name: 'idcapres', data: 'idcapres', title: 'ID Capres'},
            {name: 'idcaleg', data: 'idcaleg', title: 'ID Caleg'}
        ],
        dom: 'lftirpB',
        buttons: ['csv']
    });
});
</script>
