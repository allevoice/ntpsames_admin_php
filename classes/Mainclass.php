<?php
class Mainclass{




    protected $table;
    protected $id;
    protected $pdo;
    protected $sql_queries;
    protected $search_term;



    protected function tableExists($table) {
        try {
            $result = $this->pdoconnect()->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }
        return $result !== FALSE;
    }


    public function pdoconnect(){

        $host = $_ENV['MYSQL_HOST'] ?? 'localhost';
        $dbname = $_ENV['MYSQL_DBNAME'] ?? 'ntpsams_2025_php';
        $user = $_ENV['MYSQL_USER'] ?? 'root';
        $pwd = $_ENV['MYSQL_PWD'] ?? '';
        $utf= $_ENV['MYSQL_UTF'] ?? 'utf8';

        if(!isset($this->pdo)){
            $this->pdo = new \PDO('mysql:host='.$host.';dbname='.$dbname.';charset='.$utf, $user,$pwd);
            return $this->pdo;
        }else{
            return $this->pdo;
        }

    }




    public function readAll(){
        $stmt = $this->pdoconnect()->prepare("SELECT * FROM ".$this->table);
        $stmt->execute();
        return $stmt->fetchAll();
    }





}