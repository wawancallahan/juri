<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/middleware/hasAuth.php';

use Models\Peserta;
use Models\Penilaian;
use Models\KategoriNilai;

$pesertaModel = new Peserta($pdo);

$id = input_form($_GET['id'] ?? null);
$item = $pesertaModel->find($id);

if ($item === null) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Data Tidak Ditemukan';

    header('location: penilaian.php');
    die();
}

$penilaianModel = new Penilaian($pdo);
$countPenilaian = $penilaianModel->countPenilaian($item['id'], $_SESSION['user_id']);

if (($countPenilaian['count_penilaian'] ?? 0) > 0) {
    $_SESSION['type'] = 'warning';
    $_SESSION['message'] = 'Peserta ini telah ada penilaian sebelumnya!';

    header('location: penilaian.php');
    die();
}

$kategoriNilaiModel = new KategoriNilai($pdo);
$kategoriNilaiItems = $kategoriNilaiModel->index();

ob_start();

extract([
    'item' => $item,
    'kategoriNilaiItems' => $kategoriNilaiItems
]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard 2</title>

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
            background: url('assets/img/duta_ornament.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
        }
        .content-wrapper {
            background-color: transparent;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="assets/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

            <?php require './sidebar.php' ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-white">Penilaian</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active text-white">Penilaian</li>
                        </ol>
                    </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?php require_once __DIR__ . '/components/flash.php' ?>

                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Penilaian</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="tambah_penilaian_proses.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="nilai-peserta-avatar text-center">
                                            <?php 
                                                $currentDirectory = getcwd();
                                                $uploadDirectory = "/files/";
                                                $uploadPath = $currentDirectory . $uploadDirectory . $item['foto']; 

                                                if (file_exists($uploadPath)) {
                                            ?>
                                                <img src="<?php echo $uploadDirectory . $item['foto'] ?>" alt="" class="mb-2">
                                            <?php } ?>
                                        </div>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>Nama :</td>
                                                    <td><?php echo $item['nama'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>TTL :</td>
                                                    <td><?php echo $item['tempat'] ?>, <?php echo $item['tanggal_lahir'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Umur</td>
                                                    <td><?php echo $item['umur'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Alamat</td>
                                                    <td><?php echo $item['alamat'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Hobi</td>
                                                    <td><?php echo $item['hobi'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Pekerjaan</td>
                                                    <td><?php echo $item['pekerjaan'] ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kategori</th>
                                                        <th>Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($kategoriNilaiItems as $kategoriNilaiItem) { ?> 
                                                        <tr>
                                                            <td><?php echo $kategoriNilaiItem['nama'] ?></td>
                                                            <td>
                                                                <input type="number" name="nilai[<?php echo $kategoriNilaiItem['id'] ?>]" class="form-control input-total" min="0" max="10" value="0" required>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td>Total</td>
                                                        <td class="total">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="text-right">
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div><!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/js/adminlte.js"></script>

    <script>
        $(function () {
            $('.input-total').change(function (e) {
                let el = $(this);

                let total = 0;

                $('.input-total').each(function(i, item) {
                    let inputTotal = $(this).val() || 0;

                    if (inputTotal && !isNaN(inputTotal)) {
                        total += parseInt(inputTotal);
                    }
                });

                $('.total').html(total);
            });
        });
    </script>

</body>
</html>

<?php

$view = ob_get_clean();

reset_session_flash();

echo $view;

?>