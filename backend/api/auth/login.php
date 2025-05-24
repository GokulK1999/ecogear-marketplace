<?php
/**
 * User Login API
 * Handles user authentication
 */

require_once '../../config/cors.php';
require_once '../../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendResponse(false, 'Invalid JSON data', null, 400);
    }
    
    // Sanitize input
    $data = sanitizeInput($input);
    
    // Required fields validation
    $required_fields = ['email', 'password'];
    $missing_fields = validateRequired($data, $required_fields);
    
    if (!empty($missing_fields)) {
        sendResponse(false, 'Email and password are required', null, 400);
    }
    
    // Validate email format
    if (!validateEmail($data['email'])) {
        sendResponse(false, 'Invalid email format', null, 400);
    }
    
    // Connect to database
    $conn = getDbConnection();
    
    // Get user by email
    $query = "SELECT id, first_name, last_name, email, phone, password_hash, 
                     address, city, state, postal_code, date_of_birth, gender,
                     email_notifications, sms_notifications, newsletter, 
                     profile_visible, share_activity, data_saving, created_at
              FROM users WHERE email = :email";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $data['email']);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        sendResponse(false, 'Invalid email or password', null, 401);
    }
    
    $user = $stmt->fetch();
    
    // Verify password
    if (!verifyPassword($data['password'], $user['password_hash'])) {
        sendResponse(false, 'Invalid email or password', null, 401);
    }
    
    // Remove sensitive data
    unset($user['password_hash']);
    
    // Convert boolean fields
    $user['email_notifications'] = (bool)$user['email_notifications'];
    $user['sms_notifications'] = (bool)$user['sms_notifications'];
    $user['newsletter'] = (bool)$user['newsletter'];
    $user['profile_visible'] = (bool)$user['profile_visible'];
    $user['share_activity'] = (bool)$user['share_activity'];
    $user['data_saving'] = (bool)$user['data_saving'];
    
    // Create session token (simple approach for demo)
    $token = base64_encode(json_encode([
        'user_id' => $user['id'],
        'email' => $user['email'],
        'exp' => time() + (24 * 60 * 60) // 24 hours
    ]));
    
    sendResponse(true, 'Login successful!', [
        'user' => $user,
        'token' => $token
    ]);
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    sendResponse(false, 'An error occurred during login', null, 500);
}
?>