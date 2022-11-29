<div style="display: flex; min-height: 100vh; background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(<?php echo base_url('public/pemira/img/hero-bg.jpg'); ?>); background-size: cover; background-position: center" class="grey lighten-3">
    <div class="container center-align" style="margin: auto">
        <?php if (isset($login)): ?>
            <h1 class="white-text" style="font-weight: bold"><?php echo esc($_ENV['pemira.info.event']); ?></h1>
            <h4 class="white-text"><?php echo esc($_ENV['pemira.info.host']); ?></h4>
            <div class="row">
                <div class="col s12 m3"></div>
                <div class="col s12 m6">
                    <ul class="collection z-depth-3">
                        <li class="collection-item"><b>Nama:</b> <?php echo esc($login->Nama); ?></li>
                        <li class="collection-item"><b>NIM:</b> <?php echo esc($login->NIM); ?></li>
                        <li class="collection-item"><b>Prodi:</b> <?php echo esc($login->Prodi); ?></li>
                        <li class="collection-item"><b>Angkatan:</b> <?php echo esc($login->Angkatan); ?></li>
                        <li class="collection-item"><b>SSO:</b> <?php echo esc($login->SSO.'@'.$_ENV['pemira.mail.host']); ?></li>
                        <li class="collection-item">
                            <div><b>Jadwal:</b></div>
                            <ul>
                                <?php foreach ($listSesi as $sesi): ?>
                                    <li>- <?php echo esc($sesi->sesi_nama); ?> <a href="#!" class="tooltipped" data-position="top" data-tooltip="Informasi" onclick="showInfoSesi(event, <?php echo $sesi->sesi_id; ?>)"><i class="fa fa-info-circle"></i></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="collection-item">
                            <p><?php echo $login->SudahVote ? '<b class="green white-text" style="padding: 4px">SUDAH VOTING</b>' : '<b class="red white-text" style="padding: 4px">BELUM VOTING</b>'; ?></p>
                            <?php if ($login->SudahVote): ?>
                                <p><a href="<?php echo site_url('user/vote/downloadbukti'); ?>">Unduh bukti</a></p>
                            <?php endif; ?>
                            <p><a class="green btn-large <?php echo $login->SudahVote ? 'disabled' : ''; ?>" href="<?php echo site_url('user/vote'); ?>"><i class="fa fa-vote-yea left"></i> <b>VOTE SEKARANG</b></a></p>
                        </li>
                    </ul>
                </div>
                <div class="col s12 m3"></div>
            </div>
        <?php else: ?>
            <h1 class="white-text" style="font-weight: bold"><?php echo esc($_ENV['pemira.info.event']); ?></h1>
            <h4 class="white-text"><?php echo esc($_ENV['pemira.info.host']); ?></h4>
            <a style="margin-top: 2rem" class="btn-large" href="<?php echo site_url('user/auth/login'); ?>"><i class="fa fa-sign-in-alt left"></i> <b>MASUK</b></a>
        <?php endif; ?>
    </div>
</div>
<div class="container" style="margin-bottom: 4rem">
    <?php if ($_ENV['pemira.access'] === 'sso'): ?>
        <div style="margin: 4rem 1rem">
            <h3 class="center-align">Tata Cara E-Voting</h3>
            <ol type="a">
                <li>
                    <span>Aktivasi email SSO terlebih dahulu:</span>
                    <ol type="1">
                        <li>Klik pada tombol <b>MASUK</b> di atas</li>
                        <li>Masukkan NIM dan username SSO anda pada bagian <b>Belum Memiliki Kartu Akses?</b></li>
                        <li>Kemudian klik tombol <b>AKTIVASI</b></li>
                        <li>Setelah itu, cek pada <b>inbox atau spam</b> email SSO institusi anda untuk mendapatkan link aktivasi</li>
                        <li>Jika sudah mendapatkan e-mail yang berisi link aktivasi, klik link tersebut</li>
                        <li>Setelah itu, anda dapat mengunduh kartu akses yang tertera pada webpage tersebut</li>
                    </ol>
                </li>
                <li>
                    <span>Pelaksanaan pemilihan pada hari-H:</span>
                    <ol type="1">
                        <li>Klik pada tombol <b>MASUK</b> di atas</li>
                        <li>Masukkan kartu akses yang telah anda download pada bagian <b>Sudah Memiliki Kartu Akses?</b></li>
                        <li>Kemudian klik tombol <b>MASUK</b> pada halaman tersebut</li>
                        <li>Jika sudah merupakan jadwal prodi anda, silakan untuk melakukan voting dengan mengklik tombol <b>VOTE SEKARANG</b></li>
                        <li>Pilih pasangan capres dan cawapres, beserta caleg (jika ada)</li>
                        <li>Setelah itu, lakukan konfirmasi pemilihan dan klik pada tombol <b>KIRIM</b> jika sudah sesuai</li>
                        <li>Suara anda telah masuk ke dalam sistem</li>
                    </ol>
                </li>
            </ol>
        </div>
    <?php endif; ?>
    <div style="margin: 4rem 1rem">
        <h3 class="center-align">Jadwal</h3>
        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Sesi</th>
                    <th>Waktu Buka</th>
                    <th>Waktu Tutup</th>
                    <th>Prodi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listJadwal as $jadwal): ?>
                    <tr>
                        <td><?php echo esc($jadwal['sesi']->Nama); ?></td>
                        <td><?php echo $jadwal['sesi']->getWaktuBukaString(); ?></td>
                        <td><?php echo $jadwal['sesi']->getWaktuTutupString(); ?></td>
                        <td>
                            <ul class="browser-default">
                                <?php foreach ($jadwal['listProdi'] as $prodi): ?>
                                    <li><?php echo esc($prodi->prodi_nama); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="margin: 4rem 1rem">
        <h3 class="center-align">Live Count</h3>
        <div class="center-align" id="live-count"></div>
    </div>
</div>
<script>

function updateLiveCount() {
    $.get('home/getlivecount', null, function(data) {
        $('#live-count').html(data);
    });
}

function showInfoSesi(e, id) {
    e.preventDefault();
    $.get('home/getinfosesi', {id: id}, function(data) {
        showToast(data);
    });
}

$(document).ready(function() {
    updateLiveCount();

    window.setInterval(updateLiveCount, 15000);
});

</script>
