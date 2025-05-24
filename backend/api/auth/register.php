<?php
/**
 * User Registration API
 * Handles new user account creation
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
    $required_fields = ['firstName', 'lastName', 'email', 'phone', 'password', 'confirmPassword', 'address', 'city', 'state', 'postalCode'];
    $missing_fields = validateRequired($data, $required_fields);
    
    if (!empty($missing_fields)) {
        sendResponse(false, 'Missing required fields: ' . implode(', ', $missing_fields), null, 400);
    }
    
    // Additional validation
    if (!validateEmail($data['email'])) {
        sendResponse(false, 'Invalid email format', null, 400);
    }
    
    if ($data['password'] !== $data['confirmPassword']) {
        sendResponse(false, 'Passwords do not match', null, 400);
    }
    
    if (strlen($data['password']) < 8) {
        sendResponse(false, 'Password must be at least 8 characters long', null, 400);
    }
    
    // Validate Malaysian phone number
    $phone_pattern = '/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/';
    if (!preg_match($phone_pattern, str_replace([' ', '-'], '', $data['phone']))) {
        sendResponse(false, 'Invalid Malaysian phone number format', null, 400);
    }
    
    // Validate postal code (5 digits)
    if (!preg_match('/^\d{5}$/', $data['postalCode'])) {
        sendResponse(false, 'Postal code must be 5 digits', null, 400);
    }
    
    // Connect to database
    $conn = getDbConnection();
    
    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = :email";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(':email', $data['email']);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        sendResponse(false, 'Email address is already registered', null, 409);
    }
    
    // Hash password
    $password_hash = hashPassword($data['password']);
    
    // Insert new user
    $insert_query = "INSERT INTO users (
        first_name, last_name, email, phone, password_hash, 
        date_of_birth, address, city, state, postal_code, gender,
        email_notifications, sms_notifications, newsletter
    ) VALUES (
        :first_name, :last_name, :email, :phone, :password_hash,
        :date_of_birth, :address, :city, :state, :postal_code, :gender,
        :email_notifications, :sms_notifications, :newsletter
    )";
    
    $insert_stmt = $conn->prepare($insert_query);
    
    // Bind parameters
    $insert_stmt->bindParam(':first_name', $data['firstName']);
    $insert_stmt->bindParam(':last_name', $data['lastName']);
    $insert_stmt->bindParam(':email', $data['email']);
    $insert_stmt->bindParam(':phone', $data['phone']);
    $insert_stmt->bindParam(':password_hash', $password_hash);
    $insert_stmt->bindParam(':date_of_birth', $data['dateOfBirth'] ?? null);
    $insert_stmt->bindParam(':address', $data['address']);
    $insert_stmt->bindParam(':city', $data['city']);
    $insert_stmt->bindParam(':state', $data['state']);
    $insert_stmt->bindParam(':postal_code', $data['postalCode']);
    $insert_stmt->bindParam(':gender', $data['gender'] ?? '');
    
    // Handle boolean preferences
    $email_notifications = isset($data['newsletter']) && $data['newsletter'] ? 1 : 0;
    $sms_notifications = isset($data['smsNotifications']) && $data['smsNotifications'] ? 1 : 0;
    $newsletter = isset($data['newsletter']) && $data['newsletter'] ? 1 : 0;
    
    $insert_stmt->bindParam(':email_notifications', $email_notifications);
    $insert_stmt->bindParam(':sms_notifications', $sms_notifications);
    $insert_stmt->bindParam(':newsletter', $newsletter);
    
    // Execute query
    if ($insert_stmt->execute()) {
        $user_id = $conn->lastInsertId();
        
        // Get user data for response (without password)
        $user_query = "SELECT id, first_name, last_name, email, phone, created_at FROM users WHERE id = :id";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bindParam(':id', $user_id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch();
        
        sendResponse(true, 'Registration successful! Welcome to EcoGear!', [
            'user' => $user_data
        ], 201);
    } else {
        sendResponse(false, 'Registration failed. Please try again.', null, 500);
    }
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    sendResponse(false, 'An error occurred during registration', null, 500);
}
?>