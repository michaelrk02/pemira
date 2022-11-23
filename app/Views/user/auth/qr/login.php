<div style="display: flex; align-items: center; justify-content: center; margin-top: 4rem; margin-bottom: 4rem">
    <div style="width: 100%; max-width: 600px">
        <h3 class="center-align">Masuk</h3>
        <form method="post">
            <div class="row">
                <div class="col s12">
                    Silakan untuk memasukkan NIM anda pada kolom input di bawah
                </div>
                <div class="col s12">
                    <div class="input-field col s12">
                        <input id="f1-nim" name="nim" type="text" value="<?php echo set_value('nim'); ?>">
                        <label for="f1-nim">NIM</label>
                        <span class="helper-text">Masukkan NIM anda</span>
                    </div>
                </div>
                <div class="col s12">
                    <button type="submit" class="btn" name="submit" value="1"><i class="fa fa-sign-in-alt left"></i> <b>MASUK</b></button>
                </div>
            </div>
        </form>
    </div>
</div>
