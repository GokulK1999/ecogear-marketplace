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

/**
 * Helper function to send JSON response
 */
function sendResponse($success, $message, $data = null, $http_code = 200) {
    http_response_code($http_code);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return trim(strip_tags($data));
}

/**
 * Validate required fields
 */
function validateRequired($data, $required_fields) {
    $missing = [];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $missing[] = $field;
        }
    }
    return $missing;
}

/**
 * Validate email format
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password securely
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate secure random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Validate phone number (Malaysian format)
 */
function validateMalaysianPhone($phone) {
    $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
    return preg_match('/^(\+?6?01)[0-46-9][0-9]{7,8}$/', $cleaned);
}

/**
 * Validate postal code (Malaysian 5-digit format)
 */
function validatePostalCode($postal_code) {
    return preg_match('/^\d{5}$/', $postal_code);
}

/**
 * Log error with timestamp
 */
function logError($message, $context = []) {
    $log_message = date('Y-m-d H:i:s') . " - " . $message;
    if (!empty($context)) {
        $log_message .= " - Context: " . json_encode($context);
    }
    error_log($log_message);
}
?>