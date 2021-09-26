<?php

require __DIR__ . '/config/connect.php';
require __DIR__ . '/config/session.php';
require __DIR__ . '/config/form.php';
require __DIR__ . '/vendor/autoload.php';

use Rakit\Validation\Validator;
use Models\Peserta;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama = input_form($_POST['nama'] ?? null);
    $tempat = input_form($_POST['tempat'] ?? null);
    $tanggal_lahir = input_form($_POST['tanggal_lahir'] ?? null);
    $umur = input_form($_POST['umur'] ?? null);
    $alamat = input_form($_POST['alamat'] ?? null);
    $hobi = input_form($_POST['hobi'] ?? null);
    $pekerjaan = input_form($_POST['pekerjaan'] ?? null);
    $kategori_peserta_id = input_form($_POST['kategori_peserta_id'] ?? null);
    $foto = $_FILES['foto'] ?? null;

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
        'foto' => $foto
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

    if(isset($_FILES['foto'])){
        $currentDirectory = getcwd();
        $uploadDirectory = "/files/";
    
        $fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed 
    
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileTmpName  = $_FILES['foto']['tmp_name'];
        $fileType = $_FILES['foto']['type'];
        $fileExtension = strtolower(end(explode('.',$fileName)));
    
        $temp = explode(".", $fileName);
        $newFileName = round(microtime(true)) . '.' . end($temp);
    
        $uploadPath = $currentDirectory . $uploadDirectory . $newFileName; 
        move_uploaded_file($fileTmpName, $uploadPath);
    }

    $pesertaModel = new Peserta($pdo);
    $item = $pesertaModel->create([
        'nama' => $nama,
        'tempat' => $tempat,
        'tanggal_lahir' => $tanggal_lahir,
        'umur' => $umur,
        'alamat' => $alamat,
        'hobi' => $hobi,
        'pekerjaan' => $pekerjaan,
        'kategori_peserta_id' => $kategori_peserta_id,
        'foto' => $newFileName,
    ]);

    switch ($item) {
        case 'success':
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Data Berhasil Ditambah';

            header('location: peserta.php');
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

    header('location: tambah_peserta.php');
    die();
}

$_SESSION['type'] = 'danger';
$_SESSION['message'] = 'Terjadi Kesalahan Proses Data';

header('location: tambah_peserta.php');
die();

