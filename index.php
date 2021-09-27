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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Duta</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/adminlte.min.css">

    <style>
        body {
            background: url('assets/img/jembatan2.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            overflow: hidden;
        }
        .separator {
            position: relative;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .separator:before {
            background: #ddd;
            content: "";
            display: block;
            height: 1px;
            position: absolute;
            top: 50%;
            width: 100%;
            z-index: 0;
        }
        .separator-in {
            line-height: 1.4;
            background: #fff;
            color: #bbb;
            padding: 0 1em;
            position: relative;
        }

        .logo-login {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 150px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <div class="text-center">
                    <img src="assets/img/logo_duwis_smd.jpg" alt="" class="logo-login">
                </div>

                <?php require_once __DIR__ . '/components/flash.php' ?>

                <form action="login_proses.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/js/adminlte.js"></script>
</body>
</html>


<?php

$view = ob_get_clean();

reset_session_flash();

echo $view;

?>