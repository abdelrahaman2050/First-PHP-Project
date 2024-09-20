<?php
class Manager {
    private $conn;
    private $table = 'managers'; 

    public function __construct($db) {
        $this->conn = $db; 
    }

    public function login($email, $password) {
        $query = "SELECT id, password FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            if (password_verify($password, $stored_password)) {
                return $row['id']; 
            }
        }

        return false; 
    }

    public function register($name, $email, $password) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return false;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $email, $hashed_password]);

        return true; 
    }
}  


    
