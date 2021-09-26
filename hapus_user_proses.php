<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Models\User;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = input_form($_GET['id'] ?? null);

    $userModel = new User($pdo);
    $item = $userModel->find($id);

    if ($item === null) {
        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = 'Data Tidak Ditemukan';

        header('location: user.php');
        die();
    }

    if ($item['role'] === 'admin') {
        $_SESSION['type'] = 'warning';
        $_SESSION['message'] = 'Data Tidak Dapat Dihapus';

        header('location: user.php');
        die();
    }

    $item = $userModel->delete($id);

    switch ($item) {
        case true:
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Dihapus';

            header('location: user.php');
            die();
            break;
        case false:
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Data Gagal Dihapus';
            break;
    }

    header('location: user.php');
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: user.php');
die();