<div class="container">
    <h3><?php echo $createMode ? 'Add' : 'Edit' ?> Mahasiswa</h3>
    <form method="post" action="<?php echo $action; ?>" onsubmit="return confirm('Apakah anda yakin?')">
        <div class="input-field">
            <input id="input-NIM" type="text" name="NIM" value="<?php echo esc($mahasiswa->NIM); ?>">
            <label for="input-NIM">NIM <?php echo !$createMode ? '(sebelumnya: '.$oldNIM.')' : ''; ?></label>
        </div>
        <div class="input-field">
            <input id="input-Nama" type="text" name="Nama" value="<?php echo esc($mahasiswa->Nama); ?>">
            <label for="input-Nama">Nama</label>
        </div>
        <div class="input-field">
            <select name="IDProdi">
                <option value="" disabled <?php echo $mahasiswa->IDProdi === '' ? 'selected' : ''; ?>>Pilih prodi</option>
                <?php foreach ($listProdi as $prodi): ?>
                    <option value="<?php echo $prodi->ID; ?>" <?php echo $mahasiswa->IDProdi == $prodi->ID ? 'selected' : ''; ?>><?php echo esc($prodi->Nama); ?></option>
                <?php endforeach; ?>
            </select>
            <label>Prodi:</label>
        </div>
        <div class="input-field">
            <input id="input-Angkatan" type="number" name="Angkatan" value="<?php echo esc($mahasiswa->Angkatan); ?>">
            <label for="input-Angkatan">Angkatan</label>
        </div>
        <div class="input-field">
            <input id="input-SSO" type="text" name="SSO" value="<?php echo @esc($mahasiswa->SSO); ?>">
            <label for="input-SSO">SSO</label>
        </div>
        <p>
            <label>
                <input id="ssoCheckbox" type="checkbox" class="filled-in" onchange="updateSSOState()" <?php echo $mahasiswa->SSO === NULL ? 'checked' : ''; ?>><span>Belum mengisi SSO</span>
            </label>
        </p>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> KIRIM</button></div>
    </form>
</div>
<script>
function updateSSOState() {
    var check = document.getElementById('ssoCheckbox');
    if (check.checked) {
        $('#input-SSO').attr('disabled', 'disabled');
    } else {
        $('#input-SSO').attr('disabled', null);
    }
}
$(document).ready(function() {
    updateSSOState();
});
</script>
