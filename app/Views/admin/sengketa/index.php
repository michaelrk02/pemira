<div class="container">
    <h3>Layanan Sengketa</h3>
    <div style="margin: 1rem">
        <form method="post" action="<?php echo site_url('admin/sengketa/cekvaliditasbukti'); ?>">
            <div class="input-field">
                <input type="text" id="nim" name="nim">
                <label for="nim">NIM</label>
            </div>
            <div class="input-field">
                <input type="text" id="idcapres" name="idcapres">
                <label for="idcapres">ID Capres</label>
            </div>
            <div class="input-field">
                <input type="text" id="idcaleg" name="idcaleg">
                <label for="idcaleg">ID Caleg</label>
            </div>
            <div class="input-field">
                <input type="text" id="signature" name="signature">
                <label for="signature">Tanda Tangan Digital</label>
            </div>
            <div><button type="submit" class="btn" name="submit" value="1">CEK BUKTI</button></div>
        </form>
    </div>
</div>
