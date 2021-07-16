<?php

/**
 * Class Database
 */
class Database
{
    private $host = 'localhost';
    private $db = 'crud';
    private $user = 'postgres';
    private $password = '';
    private $pdo;

    /**
     * @return PDO
     */
    public function ConnectMe()
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
    public function Datas()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        return $data;
    }


}




