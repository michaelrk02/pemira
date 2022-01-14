<div class="container">
    <h3>Panduan Operasional</h3>
    <div style="margin: 1rem">
        <p>Secara garis besar, anda perlu mengatur data prodi, mengatur data mahasiswa, mengatur data calon (capres dan caleg), dan mengatur data sesi tiap prodi. Setelah semuanya siap dapat dilakukan pemilihan pada sesi yang telah diatur.</p>
    </div>
    <div style="margin: 1rem">
        <h5>Alur Utama</h5>
        <div>
            <ol>
                <li><b>Tambah Data Prodi.</b> Pertama-tama, <a target="_blank" href="<?php echo site_url('admin/prodi/view'); ?>">tambahkan data prodi</a> terlebih dahulu (masukkan ID bebas, umumnya berupa kode prodi yang termuat pada NIM)</li>
                <li><b>Tambah Data Mahasiswa.</b> Setelah itu, <a target="_blank" href="<?php echo site_url('admin/mahasiswa/view'); ?>">import data mahasiswa</a> untuk masing-masing prodi dengan file CSV sesuai dengan ketentuan (dengan kolom IDProdi merujuk kepada ID prodi yang telah dibuat pada saat menambah prodi). Anda bisa import lebih dari satu kali apabila file CSV terpisah berdasarkan prodi</li>
                <li><b>Tambah Data Calon.</b> Setelah semua data mahasiswa berhasil ter-import, saatnya untuk <a target="_blank" href="<?php echo site_url('admin/capres/view'); ?>">menambahkan data capres</a> dan <a target="_blank" href="<?php echo site_url('admin/caleg/view'); ?>">menambahkan data caleg (jika ada)</a>. Anda juga dapat <a target="_blank" href="<?php echo site_url('admin/resource/view'); ?>">menambahkan foto calon sekaligus</a> kemudian memasukkan ID resourcenya ke dalam data ID foto calon (<b>ingat:</b> usahakan ukuran gambar tidak terlalu besar supaya tidak memberatkan website)</li>
                <li><b>Tambah Data Sesi.</b> Sebelum pemilihan, sesi atau jadwal tiap prodi harus diatur terlebih dahulu. Anda dapat <a target="_blank" href="<?php echo site_url('admin/sesi/view'); ?>">menambahkan sesi</a> lalu <a target="_blank" href="<?php echo site_url('admin/sesi/view'); ?>">memasukkannya ke prodi</a> sesuai dengan jadwal yang ditetapkan oleh panitia. Tiap prodi bisa menempati beberapa sesi dan tiap sesi bisa ditempati beberapa prodi</li>
                <li><b>Pelaksanaan Pemungutan Suara.</b> Setelah semuanya siap, pemungutan suara dapat dilaksanakan pada tiap-tiap prodi sesuai jadwal yang telah diatur. Pastikan tiap mahasiswa melakukan aktivasi SSO terlebih dahulu kemudian menggunakan hak pilihnya pada hari pemungutan suara</li>
                <li><b>Perhitungan Suara.</b> Setelah pemungutan suara dilaksanakan, bisa langsung melakukan <a target="_blank" href="<?php echo site_url('admin/pemilih/view'); ?>">pengecekan validitas tiap suara yang masuk</a> kemudian melakukan <a target="_blank" href="<?php echo site_url('admin/rekapitulasi'); ?>">rekapitulasi perhitungan suara</a></li>
                <li><b>Semoga sukses!!</b></li>
            </ol>
        </div>
    </div>
    <div style="margin: 1rem">
        <h5>Uji Coba</h5>
        <div>
            <ol>
                <li>Anda dapat melakukan uji coba pemungutan suara berdasarkan prosedur yang tertera pada <a target="_blank" href="<?php echo site_url(); ?>">homepage</a></li>
                <li>Sebelumnya tambahkan sesi uji coba terlebih dahulu dan masukkan pada prodi yang bersangkutan</li>
                <li>Pada saat masa uji coba, dapat melakukan aktivasi akun SSO kemudian melakukan pemilihan</li>
                <li>Setelah sesi uji coba pemungutan suara selesai dan sudah melihat hasilnya, dapat menghapus sesi uji coba tersebut jika ingin dan jangan lupa untuk <b>reset data pemilih</b> <a target="_blank" href="<?php echo site_url('admin/pemilih/view'); ?>">di sini</a> supaya suara yang masuk kembali nol</li>
                <li>Anda dapat menambah, mengedit, maupun menghapus berbagai data yang ada untuk mendukung keperluan uji coba</li>
            </ol>
        </div>
    </div>
    <div style="margin: 1rem">
        <h5>Sengketa</h5>
        <div>
            <table class="striped responsive-table">
                <thead>
                    <tr>
                        <th>Dugaan</th>
                        <th>Klarifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Penggelembungan suara dengan memanfaatkan DPT yang nonaktif (umumnya memanfaatkan mahasiswa angkatan tua)</td>
                        <td>
                            Cek status aktivasi SSO DPT tersebut terlebih dahulu.
                            Setelah itu minta SSO yang valid kepada DPT yang bersangkutan (dicocokkan dengan cara mengirimkan suatu pesan ke SSO beliau kemudian beliau membacakan pesan yg telah dikirim).
                            Apabila DPT tersebut bisa menerima dan membaca pesan tersebut, maka cek data mahasiswa dengan mencocokan username SSO tersebut.
                            Jika data SSO pada website sama dengan yang diberikan tadi maka DPT terbukti berbohong dan mengada-ada (implikasi: DPT nonaktif tersebut mengklik link aktivasi dan membagikan kartu akses ke pihak yang melakukan kecurangan, atau pihak tersebut mendapat akses ke email SSO DPT, atau melakukan voting sendiri).
                            Lalu jika data SSO pada website berbeda dari yang disampaikan maka suara tergolong tidak valid (implikasi: menggunakan SSO milik mahasiswa lain atau mahasiswa pada fakultas lain) dan tindak lanjutnya adalah mengganti data username SSO pada website sesuai dengan yang diberikan DPT, kemudian DPT tersebut melakukan aktivasi untuk mendapatkan kartu akses lalu masuk ke sistem kemudian mengunduh bukti pemilihan, lalu panitia mengurangi perolehan suara calon yang terpengaruh oleh kecurangan ini (yang tertera pada bukti pemilihan DPT tersebut).
                        </td>
                    </tr>
                    <tr>
                        <td>Manipulasi suara dengan mengganti pilihan oleh hacker atau pengembang web</td>
                        <td>
                            Sistem sudah diusahakan aman dari serangan hacker.
                            Cek pada bagian "Data Pemilih" pada penjelasan kolom "Valid".
                            Mengganti pilihan capres dan caleg saja tidak cukup, melainkan juga butuh untuk mengganti signature pada tiap token yang diganti yang mana proses pembuatan signaturenya dirahasiakan oleh pengembang web.
                            Untuk pengembang web sendiri tidak mungkin melakukan manipulasi karena sudah disumpah pada saat pelantikan perangkat PEMIRA dan juga bekerja secara profesional.
                            Namun jika masih ragu-ragu dapat membuka kesempatan bagi seluruh mahasiswa yang berpartisipasi untuk mengunduh bukti pemilihan dan mengecek lagi.
                            Apabila terdapat perbedaan dengan bukti sebelumnya, segera laporkan saja supaya panitia menganggap bukti pertama yang valid (nilai timestamp lebih kecil).
                        </td>
                    </tr>
                    <tr>
                        <td>Penghapusan suara oleh hacker atau pengembang web</td>
                        <td>
                            Kurang lebih sama seperti bagian sebelumnya perihal disclaimer hacker dan pengembang webnya.
                            Untuk prosedurnya, buka kesempatan bagi seluruh mahasiswa yang berpartisipasi yang telah memilih untuk masuk kembali ke sistem.
                            Apabila statusnya adalah belum voting, maka mahasiswa tersebut berhak melakukan pemilihan (lagi), dan sekaligus membuka kesempatan lagi bagi yang belum menyuarakan haknya.
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Gunakan fitur <a target="_blank" href="<?php echo site_url('admin/sengketa'); ?>">Layanan Sengketa</a> untuk mengecek keabsahan bukti pemilihan mahasiswa</p>
        </div>
    </div>
</div>
