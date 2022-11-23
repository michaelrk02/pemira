<div style="display: flex; align-items: center; justify-content: center; margin-top: 4rem; margin-bottom: 4rem">
    <div style="width: 100%; max-width: 600px">
        <h3 class="center-align" style="margin-bottom: 2rem">Verifikasi Identitas</h3>
        <p>Tunjukkan kode QR di bawah kepada panitia untuk dapat masuk ke sistem pemungutan suara. Jangan lupa untuk menyertakan bukti identitas anda pada panitia (KTP / kartu mahasiswa / lainnya sesuai ketentuan)</p>
        <div style="display: flex; justify-content: center">
            <div id="token"></div>
        </div>
    </div>
</div>
<script>
function checkWait() {
    $.get('<?php echo site_url('user/auth/qrWait').'?token='.urlencode($token); ?>').done(function(data) {
        if (data.status) {
            location.assign('<?php echo site_url('user/auth/qrLogin').'?token='.urlencode($token); ?>');
        }
    });
}

$(document).ready(function() {
    new QRCode('token', {
        text: '<?php echo $token; ?>',
        width: 300,
        height: 300,
        correctLevel: QRCode.CorrectLevel.H
    });

    setInterval(function() {
        checkWait();
    }, 5000);
});
</script>
