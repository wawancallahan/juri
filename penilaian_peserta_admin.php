<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/middleware/hasAuth.php';

use Models\Peserta;
use Models\User;
use Models\Penilaian;

$pesertaModel = new Peserta($pdo);
$pesertaItems = $pesertaModel->index();

$userModel = new User($pdo);
$userItems = $userModel->indexWhereRoleJuri();

$penilaianModel = new Penilaian($pdo);
$penilaianItems = $penilaianModel->index();

$statusPenilaian = [];

foreach ($penilaianItems as $penilaianItem) {
    $statusPenilaian[$penilaianItem['user_id']][] = $penilaianItem;
}

ob_start();

extract([
    'pesertaItems' => $pesertaItems,
    'userItems' => $userItems,
    'statusPenilaian' => $statusPenilaian
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
                        <h1 class="m-0">Penilaian Peserta</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Penilaian Peserta</li>
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

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Peserta</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>TTL</th>
                                        <th>Umur</th>
                                        <th>Alamat</th>
                                        <th>Hobi</th>
                                        <th>Pekerjaan</th>
                                        <th>Kategori</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pesertaItems as $index => $pesertaItem) { ?>
                                        <tr>
                                            <td><?php echo $index + 1 ?></td>
                                            <td><?php echo $pesertaItem['nama'] ?></td>
                                            <td><?php echo $pesertaItem['tempat'] ?>, <?php echo $pesertaItem['tanggal_lahir'] ?></td>
                                            <td><?php echo $pesertaItem['umur'] ?></td>
                                            <td><?php echo $pesertaItem['alamat'] ?></td>
                                            <td><?php echo $pesertaItem['hobi'] ?></td>
                                            <td><?php echo $pesertaItem['pekerjaan'] ?></td>
                                            <td><?php echo $pesertaItem['kategori']['nama'] ?? "" ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-nilai-<?php echo $pesertaItem['id'] ?>">
                                                    <i class="fa fa-eye"></i> Penilaian
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="modal-nilai-<?php echo $pesertaItem['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Penilaian Juri</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama</th>
                                                                            <th>Status</th>
                                                                            <th>Option</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($userItems as $userItem) { ?>
                                                                            <tr>
                                                                                <td><?php echo $userItem['nama'] ?></td>
                                                                                <td>
                                                                                    <?php if (isset($statusPenilaian[$userItem['id']]) && !empty($statusPenilaian[$userItem['id']])) { ?>
                                                                                        <span class="btn btn-sm btn-primary">Sudah</span>
                                                                                    <?php } else { ?>
                                                                                        <span class="btn btn-sm btn-warning">Belum</span>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if (isset($statusPenilaian[$userItem['id']]) && !empty($statusPenilaian[$userItem['id']])) { ?>
                                                                                        <a href="hapus_penilaian_juri_proses.php?user_id=<?php echo $userItem['id'] ?>&peserta_id=<?php echo $pesertaItem['id'] ?>" class="btn btn-danger btn-sm">
                                                                                            <i class="fa fa-trash"></i> Hapus
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
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

</body>
</html>

<?php

$view = ob_get_clean();

reset_session_flash();

echo $view;

?>