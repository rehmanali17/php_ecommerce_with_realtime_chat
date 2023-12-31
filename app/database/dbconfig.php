<?php

class DatabaseConnection {
    private $host = 'ecommerce_dbserver';
    private $database = 'ecommerce';
    private $user = 'ecommerce_user';
    private $password = 'ecommercePassword123!';
    private $conn;

    /**
     * @return PDO
     */
    public function getConn()
    {
        return $this->conn;
    }

    // Constructor to initialize the connection
    public function __construct() {
        try {
            // Create the PDO connection
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to execute a query
    public function query($sql) {
        return $this->conn->query($sql);
    }

    // Destructor to close the connection when the object is destroyed
    public function __destruct() {
        if ($this->conn) {
            $this->conn = null;
        }
    }
}