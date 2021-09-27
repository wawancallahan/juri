<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Rakit\Validation\Validator;
use Models\Peserta;

$id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = input_form($_POST['id'] ?? null);
    $nama = input_form($_POST['nama'] ?? null);
    $tempat = input_form($_POST['tempat'] ?? null);
    $tanggal_lahir = input_form($_POST['tanggal_lahir'] ?? null);
    $umur = input_form($_POST['umur'] ?? null);
    $alamat = input_form($_POST['alamat'] ?? null);
    $hobi = input_form($_POST['hobi'] ?? null);
    $pekerjaan = input_form($_POST['pekerjaan'] ?? null);
    $kategori_peserta_id = input_form($_POST['kategori_peserta_id'] ?? null);
    $foto = $_FILES['foto'] ?? null;
    $no_peserta = input_form($_POST['no_peserta'] ?? null);

    $validator = new Validator;
    // make it
    $validation = $validator->make([
        'nama' => $nama,
        'tempat' => $tempat,
        'tanggal_lahir' => $tanggal_lahir,
        'umur' => $umur,
        'alamat' => $alamat,
        'hobi' => $hobi,
        'pekerjaan' => $pekerjaan,
        'kategori_peserta_id' => $kategori_peserta_id,
        'foto' => $foto,
        'no_peserta' => $no_peserta
    ], [
        'nama' => 'required',
        'tempat' => 'required',
        'tanggal_lahir' => 'required|date',
        'umur' => 'required',
        'alamat' => 'required',
        'hobi' => 'required',
        'pekerjaan' => 'required',
        'kategori_peserta_id' => 'required',
        'foto' => 'required',
        'no_peserta' => 'required|numeric'
    ], [
        'required' => ':attribute harus diisi',
        'foto.required' => 'Foto harus diisi',
        'tanggal_lahir.date' => 'Tanggal Lahir format salah'
    ]);

    // then validate
    $validation->validate();

    if ($validation->fails()) {
        $errors = $validation->errors();

        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = $errors->all();

        header('location: tambah_peserta.php');
        die();
    }

    $newFileName = null;

    if(isset($_FILES['foto']) && $_FILES['foto']['size'] != 0){
        $currentDirectory = getcwd();
        $uploadDirectory = "/files/";
    
        $fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed 
    
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileTmpName  = $_FILES['foto']['tmp_name'];
        $fileType = $_FILES['foto']['type'];
        $fileExtension = strtolower(end(explode('.', $fileName)));
    
        $newFileName = round(microtime(true)) . '.' . $fileExtension;
    
        $uploadPath = $currentDirectory . $uploadDirectory . $newFileName; 
        move_uploaded_file($fileTmpName, $uploadPath);
    }

    $pesertaModel = new Peserta($pdo);
    $pesertaItem = $pesertaModel->find($id);
    $item = $pesertaModel->update([
        'nama' => $nama,
        'tempat' => $tempat,
        'tanggal_lahir' => $tanggal_lahir,
        'umur' => $umur,
        'alamat' => $alamat,
        'hobi' => $hobi,
        'pekerjaan' => $pekerjaan,
        'kategori_peserta_id' => $kategori_peserta_id,
        'foto' => $newFileName,
        'no_peserta' => $no_peserta,
        'id' => $id
    ]);

    switch ($item) {
        case 'success':
            if ($newFileName !== null) {
                $currentDirectory = getcwd();
                $uploadDirectory = "/files/";
                $uploadPath = $currentDirectory . $uploadDirectory . $pesertaItem['foto']; 
    
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
            }

            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Diedit';

            header('location: peserta.php');
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

    header('location: edit_peserta.php?id=' . $id);
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: edit_peserta.php?id=' . $id);
die();