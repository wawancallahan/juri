<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/middleware/hasAuth.php';

use Models\Peserta;
use Models\KategoriPeserta;
use Models\Penilaian;

$kategoriPesertaModel = new KategoriPeserta($pdo);

$id = input_form($_GET['id'] ?? null);
$item = $kategoriPesertaModel->find($id);

if ($item === null) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Data Tidak Ditemukan';

    header('location: hasil_penilaian.php');
    die();
}

$pesertaModel = new Peserta($pdo);
$pesertaItems = $pesertaModel->indexWhereKategoriPeserta($item['id']);

$penilaianModel = new Penilaian($pdo);
$penilaianItems = $penilaianModel->index();

$penilaianItems = array_reduce($penilaianItems, function ($output, $it) {
    $output[$it['peserta_id']][] = $it;

    return $output;
}, []);

$pesertaItems = array_map(function ($it) use ($penilaianItems) {
    $nilai = 0;

    if (isset($penilaianItems[$it['id']])) {
        foreach ($penilaianItems[$it['id']] as $penilaianItem) {
            $nilai += intval($penilaianItem['nilai'], 0);
        }
    }

    $it['nilai'] = $nilai;

    return $it;
}, $pesertaItems);

$hasilPesertaItems = [];

$sortPesertaNilai = [];

foreach ($pesertaItems as $pesertaItem) {
    $sortPesertaNilai[$pesertaItem['id']] = $pesertaItem['nilai'] ?? 0;
    $hasilPesertaItems[$pesertaItem['id']] = $pesertaItem;
}

arsort($sortPesertaNilai);

ob_start();

extract([
    'pesertaItems' => $pesertaItems,
    'item' => $item
]);

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
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        body {
            background: url('assets/img/duta_ornament.png') no-repeat center center fixed;
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
            <img class="animation__wobble" src="assets/img/logo_duwis_smd.jpg" alt="AdminLTELogo" height="60" width="60">
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
                <img src="assets/img/logo_duwis_smd.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Duta</span>
            </a>

            <?php require './sidebar.php' ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?php require_once __DIR__ . '/components/flash.php' ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><?php echo $item['nama'] ?></div>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Urutan</th>
                                        <th>Peserta</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $urutan = 1; ?>
                                    <?php foreach ($sortPesertaNilai as $pesertaId => $pesertaNilai) { ?>
                                        <?php $pesertaSelected = $hasilPesertaItems[$pesertaId]; ?>
                                        <tr>
                                            <td><?php echo $urutan++ ?></td>
                                            <td>
                                                <div class="nilai-peserta-avatar">
                                                    <?php 
                                                        $currentDirectory = getcwd();
                                                        $uploadDirectory = "/files/";
                                                        $uploadPath = $currentDirectory . $uploadDirectory . $pesertaSelected['foto']; 

                                                        if (file_exists($uploadPath)) {
                                                    ?>
                                                        <img src="<?php echo $uploadDirectory . $pesertaSelected['foto'] ?>" alt="" class="mb-2">
                                                    <?php } ?>
                                                    <div class="nilai-peserta-nama"><?php echo $pesertaSelected['nama'] ?></div>
                                                </div>
                                            </td>
                                            <td><?php echo $pesertaNilai ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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