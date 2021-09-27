<?php

namespace Models;

class Penilaian {

    protected $pdo;

    public function __construct ($pdo)
    {
        $this->pdo = $pdo;
    }

    public function countPenilaian($peserta_id, $user_id)
    {
        try {
            if ($peserta_id !== "" && $user_id !== "") {

                $query = "SELECT COUNT(*) as count_penilaian FROM penilaian WHERE peserta_id = ? AND user_id = ?";
                
                $statement = $this->pdo->prepare($query);
                
                $statement->execute([
                    $peserta_id,
                    $user_id
                ]);

                if ($statement->rowCount() <= 0) {
                    return null;
                }

                return $statement->fetch(\PDO::FETCH_ASSOC);
            } else {
                return null;
               
            }
        } catch (Exception $e) {
            return null;
        } 
    }

    public function createMany($data)
    {
        try {

            $valuesQuery = array_map(function ($item) {
                return ' VALUES(null, ?, ?, ?, ?) ';
            }, $data['penilaian']);

            $query = "INSERT INTO penilaian " . implode(",", $valuesQuery);
            
            $statement = $this->pdo->prepare($query);

            $valuesExecute = [];

            foreach ($data['penilaian'] as $penilaianItems) {
                foreach ($penilaianItems as $penilaianItem) {
                    $valuesExecute[] = $penilaianItem;
                }
            }

            $execute = $statement->execute($valuesExecute);

            return $execute ? 'success' : 'fail';
        } catch (\Exception $e) {
            return 'fail';
        }
    }
}