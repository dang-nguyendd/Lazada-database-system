<?php
    class MySQLDatabase {
        private $host = "127.0.0.1";
        private $database_name = "Lazada_Database";

        private $username = "lazada_admin";
        private $password = "password";

        public $conn;
        public function getConnection($username, $password){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $username, $password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    } 
?>