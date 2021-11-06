<html>
    <head>
        <title><?php echo esc($title); ?> - PEMIRA 2021</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- jQuery -->
        <script src="<?php echo base_url('public/jquery/js/jquery-3.6.0.min.js'); ?>"></script>

        <!-- Materialize CSS -->
        <link rel="stylesheet" href="<?php echo base_url('public/materialize/css/materialize.pemira.css'); ?>">
        <script src="<?php echo base_url('public/materialize/js/materialize.min.js'); ?>"></script>

        <!-- FontAwesome -->
        <link rel="stylesheet" href="<?php echo base_url('public/fontawesome/css/all.min.css'); ?>"></link>

        <!-- marked.js -->
        <script src="<?php echo base_url('public/marked/js/marked.min.js'); ?>"></script>

        <!-- PEMIRA -->
        <script src="<?php echo base_url('public/pemira/js/pemira.js'); ?>"></script>
    </head>
    <body style="display: flex; min-height: 100vh; flex-direction: column">
        <div style="display: none" data-severity="<?php echo $status->severity; ?>" id="status"><?php echo $status->message; ?></div>
        <div style="flex: 1 0 auto">
            <header>
                <nav>
                    <div class="nav-wrapper container">
                        <a href="<?php echo site_url(); ?>" class="brand-logo">PEMIRA</a>
                        <a href="#!" data-target="nav-mobile" class="sidenav-trigger"><i class="fa fa-bars"></i></a>
                        <ul class="right hide-on-med-and-down">
                            <?php foreach ($menus as $menu): ?>
                                <li <?php echo uri_string() === $menu['site'] ? 'class="active"' : ''; ?>><a href="<?php echo site_url($menu['site']); ?>"><?php echo $menu['name']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </nav>
                <ul class="sidenav" id="nav-mobile">
                    <li><a class="subheader">PEMIRA</a></li>
                    <?php foreach ($menus as $menu): ?>
                        <li <?php echo uri_string() === $menu['site'] ? 'class="active"' : ''; ?>><a href="<?php echo site_url($menu['site']); ?>"><?php echo $menu['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </header>
            <main>
