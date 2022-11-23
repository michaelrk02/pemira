<div class="container">
    <h3>Generate Kode Akses</h3>
    <div class="row">
        <div class="col s12" style="margin-bottom: 1rem">
            <div><b>NIM</b></div>
            <div><?php echo $mhs->NIM; ?></div>
        </div>
        <div class="col s12" style="margin-bottom: 1rem">
            <div><b>Nama</b></div>
            <div><?php echo $mhs->Nama; ?></div>
        </div>
        <div class="col s12" style="margin-bottom: 1rem">
            <div><b>Prodi</b></div>
            <div><?php echo $mhs->Prodi; ?></div>
        </div>
        <div class="col s12" style="margin-bottom: 1rem">
            <div><b>Angkatan</b></div>
            <div><?php echo $mhs->Angkatan; ?></div>
        </div>
        <div class="col s12" style="margin-bottom: 1rem">
            <div><b>Kode Akses</b></div>
            <div><code><?php echo !empty($code) ? $code : '-'; ?></code></div>
        </div>
        <div class="col s12" style="margin-bottom: 1rem">
            <form method="post" onsubmit="return confirm('Apakah anda yakin?')">
                <input type="hidden" name="nim" value="<?php echo $mhs->NIM; ?>">
                <button type="submit" class="btn" name="submit" value="1"><i class="fa fa-cogs left"></i> GENERATE</button>
            </form>
        </div>
    </div>
</div>
