<div class="container">
    <h3><?php echo $createMode ? 'Add' : 'Edit' ?> Partai</h3>
    <form method="post" action="<?php echo $action; ?>" onsubmit="return confirm('Apakah anda yakin?')">
        <div class="input-field">
            <input id="input-ID" type="number" name="ID" value="<?php echo esc($partai->ID); ?>">
            <label for="input-ID">ID <?php echo !$createMode ? '(sebelumnya: '.$oldID.')' : ''; ?></label>
            <span class="helper-text">Masukkan nomor urut partai di sini</span>
        </div>
        <div class="input-field">
            <input id="input-Nama" type="text" name="Nama" value="<?php echo esc($partai->Nama); ?>">
            <label for="input-Nama">Nama</label>
        </div>
        <div class="input-field">
            <input id="input-IDFoto" type="text" name="IDFoto" value="<?php echo esc($partai->IDFoto); ?>">
            <label for="input-IDFoto">ID Foto (resource)</label>
            <span class="helper-text">Gunakan salah satu ID resource pada <a target="_blank" href="<?php echo site_url('admin/resource/view'); ?>">halaman ini</a> atau klik <b>CREATE RESOURCE</b> terlebih dahulu apabila belum mengupload foto partai</span>
        </div>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> KIRIM</button></div>
    </form>
</div>
