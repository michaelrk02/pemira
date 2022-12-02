<div style="display: flex; justify-content: center; padding-top: 2rem; padding-bottom: 2rem">
    <div style="width: 100%; max-width: 600px">
        <div id="scanner" style="width: 100%"></div>
    </div>
</div>
<script>
var lastScan = 0;
var scanner;

$(document).ready(function() {
    scanner = new Html5QrcodeScanner('scanner', {
        fps: 10,
        qrbox: {
            width: 300,
            height: 300
        },
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
        videoConstraints: {facingMode: 'environment'}
    })

    scanner.render(function(text) {
        if (lastScan + 5000 < Date.now()) {
            lastScan = Date.now();
            window.open('<?php echo site_url('admin/mahasiswa/verifikasi'); ?>?token=' + encodeURIComponent(text));
        }
    });
});

$(window).on('blur', function() {
    if (scanner.getState() == Html5QrcodeScannerState.SCANNING) {
        scanner.pause(true);
    }
});

$(window).on('focus', function() {
    if (scanner.getState() == Html5QrcodeScannerState.PAUSED) {
        scanner.resume();
    }
});
</script>
