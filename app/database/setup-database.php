<?php

// Include the DatabaseConnection class definition
require 'dbconfig.php';
/**
 * This class is intended to run migrations, and automatically setup tables in the database.
 * So, no need to use explicit SQL file to setup tables.
 */
class DatabaseMigration {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    // Method to create users table with different columns
    public function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(127) UNIQUE,
            password VARCHAR(255),
            display_name VARCHAR(255),
            mobile_number VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->db->query($sql);
            echo "users table created successfully or already exists. <br/><br/>";
        } catch (PDOException $e) {
            die("users table creation failed: " . $e->getMessage());
        }
    }

    public function createProductsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            description VARCHAR(1023),
            price FLOAT,
            quantity INT,
            file_path VARCHAR(1023),
            created_by INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )";

        try {
            $this->db->query($sql);
            echo "products table created successfully or already exists. <br/><br/>";
        } catch (PDOException $e) {
            die("products table creation failed: " . $e->getMessage());
        }
    }

    public function createMessagesTable(){
        $sql = "CREATE TABLE IF NOT EXISTS messages (
                  id INT AUTO_INCREMENT PRIMARY KEY,
                  incoming_id INT,
                  outgoing_id INT,
                  message VARCHAR(1023),
                  created_at DATETIME DEFAULT CURRENT_TIMESTAMP 
              )";
        try {
            $this->db->query($sql);
            echo "messages table created successfully or already exists. <br/><br/>";
        } catch (PDOException $e) {
            die("messages table creation failed: " . $e->getMessage());
        }
    }

}

// Usage
$db = new DatabaseConnection();
$migration = new DatabaseMigration($db);

$migration->createUsersTable();
$migration->createProductsTable();
$migration->createMessagesTable();
