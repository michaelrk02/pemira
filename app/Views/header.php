<html>
    <head>
        <title><?php echo esc($title); ?> - <?php echo $_ENV['pemira.info.title']; ?></title>
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

        <!-- jQuery DataTables -->
        <link rel="stylesheet" href="<?php echo base_url('public/datatables/css/datatables.min.css'); ?>">
        <script src="<?php echo base_url('public/datatables/js/datatables.min.js'); ?>"></script>

        <!-- Lodash -->
        <script src="<?php echo base_url('public/lodash/js/lodash.min.js'); ?>"></script>

        <!-- Chart.js -->
        <script src="<?php echo base_url('public/chart/js/chart.min.js'); ?>"></script>

        <!-- PEMIRA -->
        <script src="<?php echo base_url('public/pemira/js/pemira.js'); ?>"></script>

        <!-- QR-code generator & scanner -->
        <script src="<?php echo base_url('public/qrcode/js/qrcode.min.js'); ?>"></script>
        <script src="<?php echo base_url('public/qrcode/js/html5-qrcode.min.js'); ?>"></script>

        <!-- Additional styles -->
        <style>
            <?php if ($sidebarOnly): ?>
                header, main, footer {
                    padding-left: 300px;
                }

                @media only screen and (max-width : 992px) {
                    header, main, footer {
                        padding-left: 0;
                    }
                }
            <?php endif; ?>
        </style>
    </head>
    <body style="display: flex; min-height: 100vh; flex-direction: column">
        <div style="display: none" data-severity="<?php echo $status->severity; ?>" id="status"><?php echo $status->message; ?></div>
        <div style="flex: 1 0 auto">
            <header>
                <?php if ($_ENV['CI_ENVIRONMENT'] === 'development'): ?>
                    <div style="position: relative; height: 32px; z-index: 100">
                        <div class="orange white-text" style="position: fixed; width: 100%; height: 32px; display: flex">
                            <b style="margin: auto">WARNING: Situs ini hanya dapat digunakan untuk keperluan uji coba saja</b>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="navbar-fixed">
                    <nav>
                        <div class="nav-wrapper container">
                            <a href="<?php echo site_url(); ?>" class="brand-logo"><?php echo $_ENV['pemira.info.appname']; ?></a>
                            <a href="#!" data-target="nav-mobile" class="sidenav-trigger"><i class="fa fa-bars"></i></a>
                            <?php if (!$sidebarOnly): ?>
                                <ul class="right hide-on-med-and-down">
                                    <?php foreach ($menus as $menu): ?>
                                        <li <?php echo uri_string() === $menu['site'] ? 'class="active"' : ''; ?>><a href="<?php echo site_url($menu['site']); ?>"><?php echo $menu['name']; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>
                <ul class="sidenav <?php echo $sidebarOnly ? 'sidenav-fixed' : ''; ?>" id="nav-mobile">
                    <li><a class="subheader"><?php echo $_ENV['pemira.info.appname']; ?></a></li>
                    <?php foreach ($menus as $menu): ?>
                        <li <?php echo uri_string() === $menu['site'] ? 'class="active"' : ''; ?>><a href="<?php echo site_url($menu['site']); ?>"><?php echo $menu['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </header>
            <main>
