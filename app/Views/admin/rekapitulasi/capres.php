<div class="container">
    <div class="row" style="margin-top: 2rem; margin-bottom: 2rem">
        <?php foreach ($listCapres as $capres): ?>
            <div class="col s12 m6 l4">
                <div class="card">
                    <div class="card-image">
                        <?php if ($capres->obj->photoExists()): ?>
                            <img src="<?php echo $capres->obj->getPhotoURL(); ?>">
                        <?php else: ?>
                            <img src="<?php echo base_url('public/pemira/img/foto-default.png'); ?>">
                        <?php endif; ?>
                        <div class="card-title" style="width: 100%; background-image: linear-gradient(to top, rgba(40, 40, 40, 255), rgba(40, 40, 40, 0))">Suara: <span class="recap" data-count="<?php echo /*$capres->jumlah*/ rand(100, 1000); ?>">0</span></div>
                    </div>
                    <div class="card-content">
                        <p>No Urut: <b><?php echo esc($capres->id); ?></b></p>
                        <p>Nama: <b><?php echo esc($capres->nama); ?></b></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="margin-top: 2rem; margin-bottom: 2rem">
        <button type="button" class="waves-effect waves-light btn hitungBtn" onclick="mulaiRekapitulasi()"><i class="fa fa-chart-bar left"></i> HITUNG PEROLEHAN SUARA</button>
    </div>
</div>
<script>
function mulaiRekapitulasi() {
    if (confirm('Apakah anda yakin?')) {
        $('.hitungBtn').attr('disabled', 'disabled');

        $('.recap').each(function(i, element) {
            var id = $(element).data('id');
            var tmpCount = 0;
            var timer = setInterval(function() {
                $(element).text(tmpCount);
                if (tmpCount == $(element).data('count')) {
                    clearInterval(timer);
                }
                tmpCount++;
            }, 25);
        });
    }
}
</script>
