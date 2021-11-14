<div style="padding-top: 2rem; padding-bottom: 4rem" class="grey lighten-3">
    <div class="container">
        <h1 class="center-align">Pemilihan Raya <?php echo $_ENV['pemira.info.year']; ?></h1>
        <h4 class="center-align"><?php echo esc($_ENV['pemira.info.host']); ?></h4>
        <div style="margin-top: 2rem" class="center-align">
            <?php if (isset($login)): ?>
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
                                <p><a class="green btn-large <?php echo $login->SudahVote ? 'disabled' : ''; ?>" href="<?php echo site_url('user/vote'); ?>"><i class="fa fa-vote-yea left"></i> <b>VOTE SEKARANG</b></a></p>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 m3"></div>
                </div>
            <?php else: ?>
                <a class="btn-large" href="<?php echo site_url('user/auth/login'); ?>"><i class="fa fa-sign-in-alt left"></i> <b>MASUK</b></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container" style="margin-bottom: 4rem">
    <h3 class="center-align">Live Count</h3>
    <div class="center-align" id="live-count"></div>
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
