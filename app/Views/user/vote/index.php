<div id="detailCapres" class="modal">
    <div class="modal-content">
        <h4>Detail Capres</h4>
        <div>
            <p>No Urut: <b id="detailCapres_noUrut"></b></p>
            <p>Nama: <b id="detailCapres_nama"></b></p>
            <div>
                <h5>Visi:</h5>
                <div id="detailCapres_visi"></div>
            </div>
            <div>
                <h5>Misi:</h5>
                <div id="detailCapres_misi"></div>
            </div>
            <hr>
            <div>
                <div class="row">
                    <div class="col"><img width="96" height="96" src="<?php echo base_url('public/pemira/img/logo-kpr.png'); ?>"></div>
                    <div class="col"><img width="96" height="96" src="<?php echo base_url('public/pemira/img/ttd-kpr.png'); ?>"></div>
                </div>
                <p><i>Tertanda, <?php echo esc($_ENV['pemira.info.copyright']); ?></i></p>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close btn red white-text">TUTUP</a>
    </div>
</div>
<div class="container" style="margin-bottom: 4rem">
    <form method="post" onsubmit="return confirm('Apakah anda yakin?')">
        <div>
            <h3>Pilih Capres</h3>
            <div class="row">
                <?php foreach ($listCapres as $capres): ?>
                    <div class="col s12 m6 l4">
                        <div class="card">
                            <div class="card-image">
                                <?php if ($capres->photoExists()): ?>
                                    <img src="<?php echo $capres->getPhotoURL(); ?>">
                                <?php else: ?>
                                    <img src="<?php echo base_url('public/pemira/img/foto-default.png'); ?>">
                                <?php endif; ?>
                                <div class="card-title" style="width: 100%; background-image: linear-gradient(to top, rgba(40, 40, 40, 255), rgba(40, 40, 40, 0))">Nomor <?php echo $capres->ID; ?></div>
                                <button type="button" class="btn-floating halfway-fab waves-effect waves-light red tooltipped btnDetailCapres" data-position="top" data-tooltip="Lihat detail" data-idcapres="<?php echo $capres->ID; ?>"><i class="fa fa-eye"></i></button>
                            </div>
                            <div class="card-content">
                                <h6 class="nama-capres" style="font-weight: bold" data-idcapres="<?php echo $capres->ID; ?>"><?php echo esc($capres->Nama); ?></h6>
                            </div>
                            <div class="card-action"><button type="button" class="waves-effect waves-light btn btn-pilih-capres" data-idcapres="<?php echo $capres->ID; ?>" onclick="pilihCapres(<?php echo $capres->ID; ?>)"><i class="fa fa-check left"></i> <span class="pilihbtn"></span></button></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <h3>Pilih Caleg</h3>
            <?php if (count($listCaleg) > 0): ?>
                    <div class="row">
                        <?php foreach ($listCaleg as $caleg): ?>
                            <div class="col s12 m4 l3">
                                <div class="card">
                                    <div class="card-image">
                                        <?php if ($caleg->photoExists()): ?>
                                            <img src="<?php echo $caleg->getPhotoURL(); ?>">
                                        <?php else: ?>
                                            <img src="<?php echo base_url('public/pemira/img/foto-default.png'); ?>">
                                        <?php endif; ?>
                                        <div class="card-title" style="width: 100%; background-image: linear-gradient(to top, rgba(40, 40, 40, 255), rgba(40, 40, 40, 0))">Nomor <?php echo $caleg->ID - (int)$caleg->IDProdi * 1000; ?></div>
                                    </div>
                                    <div class="card-content">
                                        <div class="nama-caleg" data-idcaleg="<?php echo $caleg->ID; ?>"><b><?php echo esc($caleg->Nama); ?></b></div>
                                        <div>ID Caleg: <?php echo $caleg->ID; ?></div>
                                    </div>
                                    <div class="card-action"><button type="button" class="waves-effect waves-light btn btn-pilih-caleg" data-idcaleg="<?php echo $caleg->ID; ?>" onclick="pilihCaleg(<?php echo $caleg->ID; ?>)"><i class="fa fa-check left"></i> <span class="pilihbtn"></span></button></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
            <?php else: ?>
                <p>Tidak ada caleg yang harus dipilih. Silakan untuk melanjutkan ke konfirmasi pilihan</p>
            <?php endif; ?>
        </div>
        <div>
            <h3>Konfirmasi Pilihan</h3>
            <div>
                <input type="hidden" name="idcapres" id="pilihan-idcapres" value="">
                <p>Pilihan Capres: <b id="pilihan-capres">N/A</b></p>
                <?php if (count($listCaleg) > 0): ?>
                    <input type="hidden" name="idcaleg" id="pilihan-idcaleg" value="">
                    <p>Pilihan Caleg: <b id="pilihan-caleg">N/A</b></p>
                <?php endif; ?>
                <p><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> <b>KIRIM</b></button></p>
            </div>
        </div>
    </form>
</div>
<script>

var idProdi = <?php echo $login->IDProdi; ?>;

$(document).ready(function() {
    updatePilihCapresButtons();
    updatePilihCalegButtons();

    $('.btnDetailCapres').on('click', function(e) {
        $.get('vote/getdetailcapres', {id: $(e.currentTarget).data('idcapres')}, function(data) {
            $('#detailCapres_noUrut').text(data.id);
            $('#detailCapres_nama').text(data.nama);
            $('#detailCapres_visi').html(marked(data.visi));
            $('#detailCapres_misi').html(marked(data.misi));
            M.Modal.getInstance(document.getElementById('detailCapres')).open();
        });
    });
});

function updatePilihCapresButtons() {
    $('.btn-pilih-capres').removeClass(['disabled', 'green']);
    $('.btn-pilih-capres .pilihbtn').text('PILIH');

    $('.btn-pilih-capres.terpilih').addClass(['disabled', 'green']);
    $('.btn-pilih-capres.terpilih .pilihbtn').text('TERPILIH');
}

function updatePilihCalegButtons() {
    $('.btn-pilih-caleg').removeClass(['disabled', 'green']);
    $('.btn-pilih-caleg .pilihbtn').text('PILIH');

    $('.btn-pilih-caleg.terpilih').addClass(['disabled', 'green']);
    $('.btn-pilih-caleg.terpilih .pilihbtn').text('TERPILIH');
}

function pilihCapres(id) {
    $('.btn-pilih-capres').removeClass('terpilih');
    $('.btn-pilih-capres[data-idcapres="' + id + '"]').addClass('terpilih');
    updatePilihCapresButtons();

    $('#pilihan-idcapres').val(id);
    $('#pilihan-capres').text(id + ' - ' + $('.nama-capres[data-idcapres="' + id + '"]').text());
}

function pilihCaleg(id) {
    $('.btn-pilih-caleg').removeClass('terpilih');
    $('.btn-pilih-caleg[data-idcaleg="' + id + '"]').addClass('terpilih');
    updatePilihCalegButtons();

    $('#pilihan-idcaleg').val(id);
    $('#pilihan-caleg').text('ID ' + id + ' - ' + $('.nama-caleg[data-idcaleg="' + id + '"]').text());
}

</script>
