<div class="container">
    <h3><?php echo $createMode ? 'Add' : 'Edit' ?> Prodi</h3>
    <form method="post" action="<?php echo $action; ?>" onsubmit="return confirm('Apakah anda yakin?')">
        <div class="input-field">
            <input id="input-ID" type="number" name="ID" value="<?php echo esc($prodi->ID); ?>">
            <label for="input-ID">ID <?php echo !$createMode ? '(sebelumnya: '.$oldID.')' : ''; ?></label>
            <?php if (!$createMode): ?>
                <span class="helper-text">Apabila anda mengganti ID prodi ini, anda juga <b>harus</b> menyesuaikan <b>IDCaleg</b> dari tiap-tiap caleg lagi</span>
            <?php endif; ?>
        </div>
        <div class="input-field">
            <input id="input-Nama" type="text" name="Nama" value="<?php echo esc($prodi->Nama); ?>">
            <label for="input-Nama">Nama</label>
        </div>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> KIRIM</button></div>
    </form>
</div>
