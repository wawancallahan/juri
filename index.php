<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/middleware/checkAuth.php';

ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <title>Login Duta</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/auth/css/style.css">
    <link rel="stylesheet" href="assets/auth/css/components.css">

    <style>

    </style>
</head>

<body>

    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-black">
                    <div class="p-2 m-1">
                        <div>
                            <img src="assets/img/samarinda.png" alt="logo" width="120" class="mb-2">
                            <img src="assets/img/logo_duwis_smd.jpg" alt="logo" width="100" class="mb-2 rounded-circle">
                            <img src="assets/img/wonderful-Indonesia-logo-color-underbase.png" alt="logo" width="120" class="mb-2">
                        </div>
                        
                        <?php require_once __DIR__ . '/components/flash.php' ?>
                        
                        <form method="POST" action="login_proses.php">
                            <div class="form-group">
                                <label for="" class="text-white">Username</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="" class="text-white">Password</label>
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-lg icon-right btn-primary" tabindex="4">
                                    Login
                                </button>
                            </div>
                        </form>

                        
                        <div>
                            <img src="assets/img/duta-text.png" alt="" width="100%" class="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom background-rotating" 
                            data-background="https://dpmptsp.kaltimprov.go.id/admin_assets/img/unsplash/login-bg-2.jpg">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold">Selamat Datang</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Kalimantan Timur, Indonesia</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <script>
        $(function () {
            var background_url = ["assets/img/bg1.jpg", "assets/img/bg2.jpg", "https://dpmptsp.kaltimprov.go.id/admin_assets/img/unsplash/login-bg-2.jpg"];
            // Background
            $("[data-background]").each(function() {
                var me = $(this);
                me.css({
                    backgroundImage: 'url(' + me.data('background') + ')'
                });
            });

            var backrotating = $('.background-rotating');
            var current = 0;

            function nextBackground() {
                backrotating.css({
                    backgroundImage: 'url(' + background_url[current = ++current % background_url.length] + ')'
                });

                setTimeout(nextBackground, 9000);
            }
            setTimeout(nextBackground, 9000);
        });
    </script>
</body>
</html>

<?php

$view = ob_get_clean();

reset_session_flash();

echo $view;

?>