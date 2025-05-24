<?php
/**
 * Database Configuration for EcoGear
 * Uses 127.0.0.1 for Mac compatibility
 */

class Database {
    private $host = '127.0.0.1';  // Changed from localhost
    private $db_name = 'ecogear_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    /**
     * Get database connection
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed: " . $exception->getMessage());
        }

        return $this->conn;
    }

    /**
     * Close database connection
     */
    public function closeConnection() {
        $this->conn = null;
    }
}

/**
 * Helper function to get database connection
 */
function getDbConnection() {
    $database = new Database();
    return $database->getConnection();
}
?>