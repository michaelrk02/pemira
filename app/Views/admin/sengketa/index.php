<div class="container">
    <h3>Layanan Sengketa</h3>
    <div style="margin: 1rem">
        <form method="post">
            <div class="input-field">
                <input type="text" id="nim" name="nim" value="<?php echo esc($bukti['nim']); ?>">
                <label for="nim">NIM</label>
            </div>
            <div class="input-field">
                <input type="text" id="idcapres" name="idcapres" value="<?php echo esc($bukti['idcapres']); ?>">
                <label for="idcapres">ID Capres</label>
            </div>
            <div class="input-field">
                <input type="text" id="idcaleg" name="idcaleg" value="<?php echo esc($bukti['idcaleg']); ?>">
                <label for="idcaleg">ID Caleg</label>
            </div>
            <div class="input-field">
                <input type="text" id="timestamp" name="timestamp" value="<?php echo esc($bukti['timestamp']); ?>">
                <label for="timestamp">Timestamp</label>
            </div>
            <div class="input-field">
                <input type="text" id="signature" name="signature" value="<?php echo esc($bukti['signature']); ?>">
                <label for="signature">Tanda Tangan Digital</label>
            </div>
            <div><button type="submit" class="btn" name="submit" value="1">CEK BUKTI</button></div>
        </form>
    </div>
</div>
