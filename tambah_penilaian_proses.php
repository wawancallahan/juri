<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Rakit\Validation\Validator;
use Models\Penilaian;

$id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = input_form($_POST['id'] ?? null);
    $nilai = $_POST['nilai'] ?? null;

    if ($nilai === null) {
        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = 'Data Penilaian Wajib Diisi';

        header('location: tambah_penilaian.php?id=' . $id);
        die();
    }

    $user_id = $_SESSION['user_id'];

    $penilaian = [];

    foreach ($nilai as $nilaiKey => $nilaiItem) {
        $penilaian[] = [
            'peserta_id' => $id,
            'kategori_nilai_id' => $nilaiKey,
            'nilai' => $nilaiItem,
            'user_id' => $user_id
        ];
    }

    $userModel = new Penilaian($pdo);
    $item = $userModel->createMany([
        'penilaian' => $penilaian,
    ]);

    switch ($item) {
        case 'success':
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Ditambah';

            header('location: penilaian.php');
            die();
            break;
        case 'fail':
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Data Gagal Ditambah';
            break;
        case 'validation':
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Semua bidang isian wajib diisi';
            break;
    }

    header('location: tambah_penilaian.php?id=' . $id);
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: tambah_penilaian.php?id=' . $id);
die();