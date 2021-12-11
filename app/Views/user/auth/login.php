<div class="container" style="margin-top: 4rem; margin-bottom: 4rem">
    <h3>Masuk</h3>
    <div class="row">
        <div class="col s12 m6">
            <h5 class="center-align">Sudah Memiliki Kartu Akses?</h5>
            <div class="divider"></div>
            <form method="post" enctype="multipart/form-data" style="margin-top: 1rem">
                <div class="row">
                    <div class="col">
                        Silakan untuk mengunggah file kartu akses yang telah anda download apabila sudah memilikinya
                    </div>
                </div>
                <div class="row">
                    <div class="file-field input-field col s12">
                        <div class="btn blue">
                            <span><i class="fa fa-id-card left"></i> ID CARD</span>
                            <input type="file" id="input0_access_card" name="idcard">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Masukkan kartu akses di sini">
                        </div>
                        <span class="helper-text">Upload file kartu akses berformat <b>*.idc</b> yang telah anda download saat melakukan aktivasi</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <button type="submit" class="btn" name="submit" value="1"><i class="fa fa-sign-in-alt left"></i> <b>MASUK</b></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col s12 m6">
            <h5 class="center-align">Belum Memiliki Kartu Akses?</h5>
            <div class="divider"></div>
            <form method="post" style="margin-top: 1rem" onsubmit="return activateSubmit()">
                <div class="row">
                    <div class="row">
                        <div class="col s12">
                            <p>Jika belum memiliki kartu akses (atau file kartu akses hilang, atau ingin melakukan aktivasi ulang), masukkan NIM dan username SSO anda di bawah kemudian klik tombol <b>AKTIVASI</b> untuk mendapatkan kartu akses yang valid</p>
                            <p><b>PERHATIAN.</b> Tuliskan <b>tanpa</b> @<?php echo $_ENV['pemira.mail.host']; ?>. Sebagai contoh, jika email SSO anda adalah <b>alice@<?php echo $_ENV['pemira.mail.host']; ?></b>, maka anda cukup menginputkan <b>alice</b> saja pada kolom <i>Username SSO</i></p>
                        </div>
                    </div>
                    <div class="input-field col s12">
                        <input id="f1-nim" name="nim" type="text" class="validate" value="<?php echo set_value('nim'); ?>">
                        <label for="f1-nim">NIM</label>
                        <span class="helper-text">Masukkan NIM anda</span>
                    </div>
                    <div class="input-field col s12">
                        <input id="f1-sso" name="sso" type="text" class="validate" value="<?php echo set_value('sso'); ?>">
                        <label for="f1-sso">Username SSO (tanpa @<?php echo $_ENV['pemira.mail.host']; ?>)</label>
                        <span class="helper-text">Masukkan username SSO anda (tanpa <b>@<?php echo $_ENV['pemira.mail.host']; ?></b>)</span>
                    </div>
                    <div class="col s12">
                        <button type="submit" class="btn" name="submit" value="2"><i class="fa fa-user-check left"></i> <b>AKTIVASI</b></button>
                        <button type="button" class="waves-effect waves-light btn-flat" id="check-activation-status"><i class="fa fa-question-circle left"></i> <b>CEK STATUS</b></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

function activateSubmit() {
    var mailHost = '<?php echo $_ENV['pemira.mail.host']; ?>';
    var nim = $('#f1-nim').val();
    var sso = $('#f1-sso').val();

    if (sso.endsWith('@' + mailHost)) {
        alert('Mohon cek penulisan username SSO lagi. Anda tidak perlu memasukkan @' + mailHost);
        return false;
    }

    return confirm('NIM: ' + nim + ', E-mail SSO: ' + sso + '@' + mailHost + '. Apakah informasi tersebut sudah benar?');
}

$(document).ready(function() {
    $('#check-activation-status').on('click', function() {
        $.get('checkstatus', {nim: $('#f1-nim').val(), sso: $('#f1-sso').val()}, function(data) {
            showToast(data);
        });
    });
});

</script>
