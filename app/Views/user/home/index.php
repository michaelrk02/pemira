<div style="padding-top: 2rem; padding-bottom: 4rem" class="grey lighten-3">
    <div class="container">
        <h1 class="center-align">Pemilihan Raya 2021</h1>
        <h4 class="center-align">Fakultas MIPA Universitas Sebelas Maret</h4>
        <div style="margin-top: 2rem" class="center-align">
            <?php if (isset($login)): ?>
                <a class="btn-large" href="<?php echo site_url('user/vote'); ?>"><i class="fa fa-vote-yea left"></i> <b>VOTE SEKARANG</b></a>
            <?php else: ?>
                <a class="btn-large" href="<?php echo site_url('user/auth/login'); ?>"><i class="fa fa-sign-in-alt left"></i> <b>MASUK</b></a>
            <?php endif; ?>
        </div>
    </div>
</div>
