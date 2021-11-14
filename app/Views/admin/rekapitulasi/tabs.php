<div class="container">
    <h3>Rekapitulasi Suara</h3>
    <div>
        <ul class="tabs">
            <li class="tab col"><a <?php echo uri_string() === 'admin/rekapitulasi/capres' ? 'class="active"' : ''; ?> target="_self" href="<?php echo site_url('admin/rekapitulasi/capres'); ?>">CAPRES</a></li>
            <li class="tab col"><a <?php echo uri_string() === 'admin/rekapitulasi/caleg' ? 'class="active"' : ''; ?> target="_self" href="<?php echo site_url('admin/rekapitulasi/caleg'); ?>">CALEG</a></li>
        </ul>
    </div>
</div>
