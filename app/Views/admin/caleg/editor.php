<div class="container">
    <h3><?php echo $createMode ? 'Add' : 'Edit' ?> Caleg</h3>
    <form method="post" action="<?php echo $action; ?>" onsubmit="return confirm('Apakah anda yakin?')">
        <div class="input-field">
            <input id="input-ID" type="number" name="ID" value="<?php echo esc($caleg->ID); ?>">
            <label for="input-ID">ID * <?php echo !$createMode ? '(sebelumnya: '.$oldID.')' : ''; ?></label>
            <span class="helper-text">*) Pastikan ID caleg adalah dengan rumus: <b>IDProdi &times; 1000 + NoUrutCaleg</b>. Sehingga misalkan terdapat caleg dengan nomor urut <b>1</b> yang berada di daerah pilihan prodi Informatika (misal IDProdi: <b>5</b>), maka ID caleg tersebut adalah <b>5001</b>. IDProdi dapat dicari di bawah</span>
        </div>
        <div class="input-field">
            <input id="input-Nama" type="text" name="Nama" value="<?php echo esc($caleg->Nama); ?>">
            <label for="input-Nama">Nama</label>
        </div>
        <div class="input-field">
            <select name="IDProdi">
                <option value="0" <?php echo ($caleg->IDProdi === NULL) || ($caleg->IDProdi === '') ? 'selected' : ''; ?>>-- Umum -- (IDProdi: 0)</option>
                <?php foreach ($listProdi as $prodi): ?>
                    <option value="<?php echo $prodi->ID; ?>" <?php echo $caleg->IDProdi == $prodi->ID ? 'selected' : ''; ?>><?php echo esc($prodi->Nama); ?> (IDProdi: <?php echo $prodi->ID; ?>)</option>
                <?php endforeach; ?>
            </select>
            <label>Prodi:</label>
            <span class="helper-text">Prodi umum artinya caleg tersebut bisa dipilih oleh semua prodi</span>
        </div>
        <div class="input-field">
            <input id="input-IDFoto" type="text" name="IDFoto" value="<?php echo esc($caleg->IDFoto); ?>">
            <label for="input-IDFoto">ID Foto (resource)</label>
            <span class="helper-text">Gunakan salah satu ID resource pada <a target="_blank" href="<?php echo site_url('admin/resource/view'); ?>">halaman ini</a> atau klik <b>CREATE RESOURCE</b> terlebih dahulu apabila belum mengupload foto calon</span>
        </div>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> KIRIM</button></div>
    </form>
</div>
