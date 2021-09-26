<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Rakit\Validation\Validator;
use Models\User;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = input_form($_POST['nama'] ?? null);
    $username = input_form($_POST['username'] ?? null);
    $password = input_form($_POST['password'] ?? null);
    $role = input_form($_POST['role'] ?? null);

    $validator = new Validator;
    // make it
    $validation = $validator->make([
        'nama' => $nama,
        'username' => $username,
        'password' => $password,
        'role' => $role
    ], [
        'nama' => 'required',
        'username' => 'required',
        'password' => 'required',
        'role' => 'required',
    ], [
        'required' => ':attribute harus diisi'
    ]);

    // then validate
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();

        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = $errors->all();

        header('location: tambah_user.php');
        die();
    }

    $userModel = new User($pdo);
    $item = $userModel->create([
        'nama' => $nama,
        'username' => $username,
        'password' => $password,
        'role' => $role
    ]);

    switch ($item) {
        case 'success':
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Ditambah';

            header('location: user.php');
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

    header('location: tambah_user.php');
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: tambah_user.php');
die();