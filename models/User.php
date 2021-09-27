<?php

namespace Models;

class User {

    protected $pdo;

    public function __construct ($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findUsernameAndPassword($username, $password)
    {
        try {
            if ($username !== "" && $password !== "") {

                $password = md5($password);
    
                $query = "SELECT * FROM user WHERE username = ? AND password = ?";
                
                $statement = $this->pdo->prepare($query);
                
                $statement->execute([
                    $username,
                    $password
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

    public function index ()
    {
        $query = "SELECT * FROM user";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function indexWhereRoleJuri () {
        $query = "SELECT * FROM user where role = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([
            'juri'
        ]);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function create ($data)
    {
        try {
            $nama = $data['nama'] ?? "";
            $username = $data['username'] ?? "";
            $password = $data['password'] ?? "";
            $role = $data['role'] ?? "";

            if ($nama !== "" && $username !== "" && $password !== "" && $role !== "") {
                
                $password = md5($password);

                $query = "INSERT INTO user VALUES(null, ?, ?, ?, ?)";
                
                $statement = $this->pdo->prepare($query);
                
                $execute = $statement->execute([
                    $nama,
                    $username,
                    $password,
                    $role
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
    
                $query = "SELECT * FROM user WHERE id = ?";
                
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
            $username = $data['username'] ?? "";
            $password = $data['password'] ?? "";
            $role = $data['role'] ?? "";

            if ($id !== "" && $nama !== "" && $username !== "" && $role != "") {

                if ($password !== "") {
                    
                    $password = md5($password);

                    $query = "UPDATE user SET nama = ?, username = ?, password = ?, role = ? WHERE id = ?";
                
                    $statement = $this->pdo->prepare($query);
                    
                    $execute = $statement->execute([
                        $nama,
                        $username,
                        $password,
                        $role,
                        $id
                    ]);
                } else {
                    $query = "UPDATE user SET nama = ?, username = ?, role = ? WHERE id = ?";
                
                    $statement = $this->pdo->prepare($query);
                    
                    $execute = $statement->execute([
                        $nama,
                        $username,
                        $role,
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
    
                $query = "DELETE FROM user WHERE id = ?";
                
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