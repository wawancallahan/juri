<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Models\Peserta;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = input_form($_GET['id'] ?? null);

    $pesertaModel = new Peserta($pdo);
    $pesertaItem = $pesertaModel->find($id);
    $item = $pesertaModel->delete($id);

    switch ($item) {
        case true:
            $currentDirectory = getcwd();
            $uploadDirectory = "/files/";
            $uploadPath = $currentDirectory . $uploadDirectory . $pesertaItem['foto']; 

            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }

            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Dihapus';

            header('location: peserta.php');
            die();
            break;
        case false:
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Data Gagal Dihapus';
            break;
    }

    header('location: peserta.php');
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: kategori_peserta.php');
die();