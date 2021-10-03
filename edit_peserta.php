<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/middleware/hasAuth.php';

use Models\Peserta;
use Models\KategoriPeserta;

$pesertaModel = new Peserta($pdo);

$id = input_form($_GET['id'] ?? null);
$item = $pesertaModel->find($id);

if ($item === null) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Data Tidak Ditemukan';

    header('location: peserta.php');
    die();
}

$kategoriPesertaModels = new KategoriPeserta($pdo);
$kategoriPesertaItems = $kategoriPesertaModels->index();


ob_start();

extract([
    'item' => $item,
    'kategoriPesertaItems' => $kategoriPesertaItems
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
    <link rel="stylesheet" href="plugins/datepicker/datepicker.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        body {
            background: url('assets/img/bg2.jpg') no-repeat center center fixed;
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
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-white">Peserta</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item text-white"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active text-white">Peserta</li>
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
                            <h3 class="card-title">Edit Peserta</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="edit_peserta_proses.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
                            <div class="card-body">

                                <div class="form-group">
                                        <label>No. Peserta</label>
                                    <input type="number" name="no_peserta" class="form-control" placeholder="No Peserta" min="0" value="<?php echo $item['no_peserta'] ?? 0 ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?php echo $item['nama'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tempat</label>
                                    <input type="text" name="tempat" class="form-control" placeholder="Tempat" value="<?php echo $item['tempat'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="text" name="tanggal_lahir" class="form-control" data-toggle="datepicker" placeholder="Tanggal Lahir" value="<?php echo $item['tanggal_lahir'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Umur</label>
                                    <input type="text" name="umur" class="form-control" placeholder="Umur" value="<?php echo $item['umur'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="<?php echo $item['alamat'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Hobi</label>
                                    <input type="text" name="hobi" class="form-control" placeholder="Hobi" value="<?php echo $item['hobi'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan" value="<?php echo $item['pekerjaan'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="kategori_peserta_id" id="" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($kategoriPesertaItems as $kategoriPesertaItem) { ?> 
                                            <option value="<?php echo $kategoriPesertaItem['id'] ?>" <?php echo $item['kategori_peserta_id'] == $kategoriPesertaItem['id'] ? 'selected' : null ?>><?php echo $kategoriPesertaItem['nama'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <?php 
                                        $currentDirectory = getcwd();
                                        $uploadDirectory = "/files/";
                                        $uploadPath = $currentDirectory . $uploadDirectory . $item['foto']; 

                                        if (file_exists($uploadPath)) {
                                    ?>
                                        <div class="form-group">
                                            <img src="<?php echo $uploadDirectory . $item['foto'] ?>" alt="" class="image-foto">
                                        </div>
                                    <?php } ?>
                                    <input type="file" name="foto" class="form-control">
                                    <p><i>* Silahkan pilih foto, jika ingin mengubah foto anda!</i></p>
                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
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

    <script src="plugins/datepicker/datepicker.js"></script>

    <script>
        $(function () {
            $('[data-toggle="datepicker"]').datepicker({
                format: 'yyyy-mm-dd'
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