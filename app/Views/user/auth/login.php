<div class="container" style="margin-top: 4rem; margin-bottom: 4rem">
    <div class="row">
        <div class="col s12 m6">
            <h5 class="center-align">Sudah Memiliki Kartu Akses?</h5>
            <div class="divider"></div>
            <form method="post" enctype="multipart/form-data" style="margin-top: 1rem">
                <div class="row">
                    <div class="file-field input-field col s12">
                        <div class="btn blue">
                            <span><i class="fa fa-id-card left"></i> ID CARD</span>
                            <input type="file" id="input0_access_card" name="idcard">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
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
            <form method="post" style="margin-top: 1rem">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="f1-nim" name="nim" type="text" class="validate" value="<?php echo set_value('nim'); ?>">
                        <label for="f1-nim">NIM</label>
                        <span class="helper-text">Masukkan NIM anda</span>
                    </div>
                    <div class="input-field col s12">
                        <input id="f1-sso" name="sso" type="text" class="validate" value="<?php echo set_value('sso'); ?>">
                        <label for="f1-sso">Username SSO</label>
                        <span class="helper-text">Masukkan username SSO anda (tanpa <b>@student.uns.ac.id</b>)</span>
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

$(document).ready(function() {
    $('#check-activation-status').on('click', function() {
        $.get('checkstatus', {nim: $('#f1-nim').val(), sso: $('#f1-sso').val()}, function(data) {
            showToast('checkstatus', data);
        });
    });
});

</script>
