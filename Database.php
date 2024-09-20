<?php

class Database
{
    private $host = "localhost";
    private $db_name = "project1";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect(): PDO
    {
        $this->conn = null;
        $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

        if (!$this->conn) {
            echo "Failed to connect to database.";
        }
        

        return $this->conn;
    }
}
