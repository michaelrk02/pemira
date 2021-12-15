            </main>
        </div>
        <footer class="page-footer white-links">
            <div class="container">
                <div class="row">
                    <div class="col s12 m6">
                        <div class="row" style="display: flex">
                            <div class="col" style="margin-top: auto; margin-bottom: auto">
                                <img width="128" height="128" src="<?php echo base_url('public/pemira/img/logo-kpr.png'); ?>">
                            </div>
                            <div class="col">
                                <h5>Hubungi Kami</h5>
                                <p><i style="margin-right: 0.5rem" class="fab fa-whatsapp"></i> <a href="https://wa.me/<?php echo $_ENV['pemira.info.cpno']; ?>">+<?php echo $_ENV['pemira.info.cpno']; ?> (<?php echo $_ENV['pemira.info.cpname']; ?>)</a></p>
                                <p><i style="margin-right: 0.5rem" class="fab fa-instagram"></i> <a href="https://instagram.com/<?php echo $_ENV['pemira.info.ig']; ?>"><?php echo $_ENV['pemira.info.ig']; ?></a></p>
                                <p><i style="margin-right: 0.5rem" class="fa fa-envelope"></i> <a href="mailto:<?php echo $_ENV['pemira.info.email']; ?>"><?php echo $_ENV['pemira.info.email']; ?></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <p>Website resmi <?php echo $_ENV['pemira.info.title']; ?></p>
                        <p>Dikembangkan dan dipelihara oleh Komisi Operasional dan Publikasi KPR FMIPA UNS 2021</p>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    <div class="center-align">Copyright &copy; <?php echo $_ENV['pemira.info.copyright']; ?></div>
                </div>
            </div>
        </footer>
    </body>
</html>

