<?php

/**
 * Class Database
 */
class Database
{
    private $host = 'localhost';
    private $db = 'crud';
    private $user = 'postgres';
    private $password = 'password';
    private $pdo;

    /**
     * @return PDO
     */
    public function connectme()
    {
        try {
            $this->pdo = new PDO("pgsql:host=$this->host,dbname=$this->db", $this->user, $this->password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $this->pdo;
    }

    /**
     * @return mixed
     */
    public function datas()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        return $data;
    }

}




