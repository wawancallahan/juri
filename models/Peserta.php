<?php

namespace Models;

class Peserta {

    protected $pdo;

    public function __construct ($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index ()
    {
        $query = "SELECT peserta.*, kategori_peserta.nama AS kategori_peserta_nama FROM peserta LEFT JOIN kategori_peserta ON kategori_peserta.id = peserta.kategori_peserta_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $result = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'nama' => $item['nama'],
                'tempat' => $item['tempat'],
                'tanggal_lahir' => $item['tanggal_lahir'],
                'umur' => $item['umur'],
                'alamat' => $item['alamat'],
                'hobi' => $item['hobi'],
                'pekerjaan' => $item['pekerjaan'],
                'foto' => $item['foto'],
                'kategori' => [
                    'nama' => $item['kategori_peserta_nama']
                ],
            ];
        }, $result);

        return $result;
    }

    public function create ($data)
    {
        try {
            $nama = $data['nama'] ?? "";
            $tempat = $data['tempat'] ?? "";
            $tanggal_lahir = $data['tanggal_lahir'] ?? "";
            $umur = $data['umur'] ?? "";
            $alamat = $data['alamat'] ?? "";
            $hobi = $data['hobi'] ?? "";
            $pekerjaan = $data['pekerjaan'] ?? "";
            $foto = $data['foto'] ?? "";
            $kategori_peserta_id = $data['kategori_peserta_id'] ?? "";

            if ($nama !== "" && $tempat !== "" && $tanggal_lahir !== "" && $umur !== ""
                && $alamat !== "" && $hobi !== "" && $pekerjaan !== "" && $foto !== "" 
                && $kategori_peserta_id !== "") {

                $query = "INSERT INTO peserta VALUES(null, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $statement = $this->pdo->prepare($query);
                
                $execute = $statement->execute([
                    $nama,
                    $tempat,
                    $tanggal_lahir,
                    $umur,
                    $alamat,
                    $hobi,
                    $pekerjaan,
                    $foto,
                    $kategori_peserta_id
                ]);

                return $execute ? 'success' : 'fail';
            } else {
                return 'validation';
               
            }
        } catch (\Exception $e) {
            return 'fail';
        }    
    }

    public function find($id)
    {
        try {
            if ($id !== "") {
    
                $query = "SELECT * FROM peserta WHERE id = ?";
                
                $statement = $this->pdo->prepare($query);
                
                $statement->execute([
                    $id,
                ]);

                if ($statement->rowCount() <= 0) {
                    return null;
                }

                return $statement->fetch(\PDO::FETCH_ASSOC);
            } else {
                return null;
               
            }
        } catch (\Exception $e) {
            return null;
        } 
    }

    public function update ($data)
    {
        try {
            $id = $data['id'] ?? "";
            $nama = $data['nama'] ?? "";
            $tempat = $data['tempat'] ?? "";
            $tanggal_lahir = $data['tanggal_lahir'] ?? "";
            $umur = $data['umur'] ?? "";
            $alamat = $data['alamat'] ?? "";
            $hobi = $data['hobi'] ?? "";
            $pekerjaan = $data['pekerjaan'] ?? "";
            $foto = $data['foto'] ?? "";
            $kategori_peserta_id = $data['kategori_peserta_id'] ?? "";

            if ($id !== "" && $nama !== "" && $tempat !== "" && $tanggal_lahir !== "" && $umur !== ""
                && $alamat !== "" && $hobi !== "" && $pekerjaan !== "" && $kategori_peserta_id !== "") {

                if ($foto !== "") {

                    $query = "UPDATE user SET nama = ?, tempat = ?, tanggal_lahir = ?, umur = ?, alamat = ?, hobi = ?, pekerjaan = ?, foto = ?, kategori_peserta_id = ? WHERE id = ?";
                
                    $statement = $this->pdo->prepare($query);
                    
                    $execute = $statement->execute([
                        $nama,
                        $tempat,
                        $tanggal_lahir,
                        $umur,
                        $alamat,
                        $hobi,
                        $pekerjaan,
                        $foto,
                        $kategori_peserta_id,
                        $id
                    ]);
                } else {
                    $query = "UPDATE user SET nama = ?, tempat = ?, tanggal_lahir = ?, umur = ?, alamat = ?, hobi = ?, pekerjaan = ?, kategori_peserta_id = ? WHERE id = ?";
                
                    $statement = $this->pdo->prepare($query);
                    
                    $execute = $statement->execute([
                        $nama,
                        $tempat,
                        $tanggal_lahir,
                        $umur,
                        $alamat,
                        $hobi,
                        $pekerjaan,
                        $kategori_peserta_id,
                        $id
                    ]);
                }

                return $execute ? 'success' : 'fail';
            } else {
                return 'validation';
               
            }
        } catch (\Exception $e) {
            return 'fail';
        }    
    }

    public function delete($id)
    {
        try {
            if ($id !== "") {
    
                $query = "DELETE FROM peserta WHERE id = ?";
                
                $statement = $this->pdo->prepare($query);
                
                $execute = $statement->execute([
                    $id,
                ]);

                return $execute;
            } else {
                return false;
               
            }
        } catch (\Exception $e) {
            return false;
        } 
    }
}