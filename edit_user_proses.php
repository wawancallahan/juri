<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Rakit\Validation\Validator;
use Models\User;

$id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = input_form($_POST['id'] ?? null);
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
        'password' => 'nullable',
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

        header('location: edit_user.php?id=' . $id);
        die();
    }

    $userModel = new User($pdo);
    $item = $userModel->update([
        'nama' => $nama,
        'username' => $username,
        'password' => $password,
        'role' => $role,
        'id' => $id
    ]);

    switch ($item) {
        case 'success':
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Diedit';

            header('location: user.php');
            die();
            break;
        case 'fail':
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Data Gagal Diedit';
            break;
        case 'validation':
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Semua bidang isian wajib diisi';
            break;
    }

    header('location: edit_user.php?id=' . $id);
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: edit_user.php?id=' . $id);
die();