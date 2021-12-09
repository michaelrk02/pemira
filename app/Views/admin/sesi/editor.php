<div class="container">
    <h3><?php echo $createMode ? 'Add' : 'Edit' ?> Sesi</h3>
    <form method="post" action="<?php echo $action; ?>" onsubmit="return confirm('Apakah anda yakin?')">
        <div class="input-field">
            <input id="input-ID" type="number" name="ID" value="<?php echo esc($sesi->ID); ?>">
            <label for="input-ID">ID <?php echo !$createMode ? '(sebelumnya: '.$oldID.')' : ''; ?></label>
        </div>
        <div class="input-field">
            <input id="input-Nama" type="text" name="Nama" value="<?php echo esc($sesi->Nama); ?>">
            <label for="input-Nama">Nama</label>
        </div>
        <div class="input-field">
            <input id="input-WaktuBukaDate" type="text" name="WaktuBukaDate" class="datepicker no-autoinit" value="<?php echo esc(explode(' ', $sesi->getWaktuBukaString())[0]); ?>">
            <label for="input-WaktuBukaDate">Waktu Buka (Tanggal)</label>
        </div>
        <div class="input-field">
            <input id="input-WaktuBukaTime" type="text" name="WaktuBukaTime" class="timepicker no-autoinit" value="<?php echo esc(explode(' ', $sesi->getWaktuBukaString())[1]); ?>">
            <label for="input-WaktuBukaTime">Waktu Buka (Waktu)</label>
        </div>
        <div class="input-field">
            <input id="input-WaktuTutupDate" type="text" name="WaktuTutupDate" class="datepicker no-autoinit" value="<?php echo esc(explode(' ', $sesi->getWaktuTutupString())[0]); ?>">
            <label for="input-WaktuTutupDate">Waktu Tutup (Tanggal)</label>
        </div>
        <div class="input-field">
            <input id="input-WaktuTutupTime" type="text" name="WaktuTutupTime" class="timepicker no-autoinit" value="<?php echo esc(explode(' ', $sesi->getWaktuTutupString())[1]); ?>">
            <label for="input-WaktuTutupTime">Waktu Tutup (Waktu)</label>
        </div>
        <div><button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> KIRIM</button></div>
    </form>
</div>
<script>
$(document).ready(function() {
    $('.datepicker').datepicker({format: 'yyyy-mm-dd', setDefaultDate: true});
    $('.timepicker').timepicker({twelveHour: false});
});
</script>
