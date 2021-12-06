<div class="container" style="margin-top: 4rem; margin-bottom: 4rem">
    <div class="card teal darken-3">
        <div class="card-content white-text">
            <div class="card-title">Berhasil Melakukan Aktivasi</div>
            <div style="margin-bottom: 1rem">
                <p>NIM : <b><?php echo esc($mhs->nim); ?></b></p>
                <p>SSO : <b><?php echo esc($mhs->sso.'@'.$_ENV['pemira.mail.host']); ?></b></p>
                <p>Nama : <b><?php echo esc($mhs->nama); ?></b></p>
                <p>Prodi : <b><?php echo esc($mhs->prodi); ?></b></p>
                <p>Angkatan : <b><?php echo esc($mhs->angkatan); ?></b></p>
            </div>
            <p style="margin-bottom: 1rem">
                Silakan untuk mengunduh kartu akses dengan mengklik tombol di bawah.
                Kartu akses digunakan untuk masuk ke dalam sistem e-voting pada saat hari pemungutan suara.
                <b>Dilarang membagikan kartu akses ke siapapun bahkan oleh pihak yang mengaku sebagai panitia!</b>
                Kartu akses masih bisa diunduh selama link aktivasi ini masih aktif.
            </p>
            <p>
                <b>NOTE:</b> file kartu akses hanya merupakan file biasa yang hanya dapat dibaca dan dikenali oleh sistem e-voting sehingga file tersebut tidak perlu dibuka menggunakan aplikasi apapun
            </p>
        </div>
        <div class="card-action">
            <a href="<?php echo $accessCard; ?>" class="btn-flat"><i class="fa fa-id-card left"></i> DOWNLOAD KARTU AKSES</a>
        </div>
    </div>
</div>
