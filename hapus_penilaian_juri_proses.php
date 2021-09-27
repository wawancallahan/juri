<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Models\Penilaian;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user_id = input_form($_GET['user_id'] ?? null);
    $peserta_id = input_form($_GET['peserta_id'] ?? null);

    $penilaianModel = new Penilaian($pdo);
    $item = $penilaianModel->deletePenilaian($user_id, $peserta_id);

    switch ($item) {
        case true:
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Dihapus';

            header('location: penilaian_peserta_admin.php');
            die();
            break;
        case false:
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Data Gagal Dihapus';
            break;
    }

    header('location: penilaian_peserta_admin.php');
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: penilaian_peserta_admin.php');
die();