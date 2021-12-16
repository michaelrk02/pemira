<div class="container">
    <div style="margin-bottom: 2rem">
        <h3>Import Mahasiswa</h3>
        <form method="post" enctype="multipart/form-data" style="margin-top: 1rem" onsubmit="confirm('Apakah anda yakin?')">
            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn blue">
                        <span><i class="fa fa-table left"></i> CSV</span>
                        <input type="file" id="input-source" name="source">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                    <span class="helper-text">Upload file bertipe <b>*.csv</b> yang memiliki header: <b>NIM, Nama, IDProdi, Angkatan</b>, maksimal berukuran <b><?php echo ini_get('upload_max_filesize'); ?>B</b></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button type="submit" class="btn green" name="submit" value="1"><i class="fa fa-paper-plane left"></i> <b>KIRIM</b></button>
                </div>
            </div>
        </form>
    </div>
    <div style="margin-bottom: 2rem">
        <p>Contoh spreadsheet (bisa dilakukan <b>save as CSV</b>):</p>
        <div><img class="responsive-img" src="<?php echo base_url('public/pemira/img/panduan-csv.png'); ?>"></div>
    </div>
</div>
