<?php
/**
 * User Profile Update API
 * Updates user profile information
 */

// Turn off HTML error display for clean JSON responses
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../../config/cors.php';
require_once '../../config/database.php';

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Method not allowed', null, 405);
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendResponse(false, 'Invalid JSON data', null, 400);
    }
    
    // Log the received data for debugging
    error_log("Profile update attempt: " . json_encode($input));
    
    // Sanitize input
    $data = sanitizeInput($input);
    
    // Required fields validation
    $required_fields = ['user_id', 'firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'postalCode'];
    $missing_fields = validateRequired($data, $required_fields);
    
    if (!empty($missing_fields)) {
        sendResponse(false, 'Missing required fields: ' . implode(', ', $missing_fields), null, 400);
    }
    
    // Additional validation
    if (!validateEmail($data['email'])) {
        sendResponse(false, 'Invalid email format', null, 400);
    }
    
    // Validate postal code (5 digits)
    if (!preg_match('/^\d{5}$/', $data['postalCode'])) {
        sendResponse(false, 'Postal code must be 5 digits', null, 400);
    }
    
    // Connect to database
    $conn = getDbConnection();
    
    // Check if user exists
    $check_query = "SELECT id FROM users WHERE id = :user_id";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bindParam(':user_id', $data['user_id']);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() === 0) {
        sendResponse(false, 'User not found', null, 404);
    }
    
    // Check if email is already taken by another user
    $email_check_query = "SELECT id FROM users WHERE email = :email AND id != :user_id";
    $email_check_stmt = $conn->prepare($email_check_query);
    $email_check_stmt->bindParam(':email', $data['email']);
    $email_check_stmt->bindParam(':user_id', $data['user_id']);
    $email_check_stmt->execute();
    
    if ($email_check_stmt->rowCount() > 0) {
        sendResponse(false, 'Email address is already registered to another account', null, 409);
    }
    
    // Update user profile
    $update_query = "UPDATE users SET 
        first_name = :first_name,
        last_name = :last_name,
        email = :email,
        phone = :phone,
        date_of_birth = :date_of_birth,
        address = :address,
        city = :city,
        state = :state,
        postal_code = :postal_code,
        gender = :gender,
        email_notifications = :email_notifications,
        sms_notifications = :sms_notifications,
        newsletter = :newsletter,
        profile_visible = :profile_visible,
        share_activity = :share_activity,
        data_saving = :data_saving,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = :user_id";
    
    $update_stmt = $conn->prepare($update_query);
    
    // Handle optional fields
    $dateOfBirth = isset($data['dateOfBirth']) && !empty($data['dateOfBirth']) ? $data['dateOfBirth'] : null;
    $gender = isset($data['gender']) && !empty($data['gender']) ? $data['gender'] : '';
    
    // Handle preferences with defaults
    $preferences = $data['preferences'] ?? [];
    $emailNotifications = isset($preferences['emailNotifications']) && $preferences['emailNotifications'] ? 1 : 0;
    $smsNotifications = isset($preferences['smsNotifications']) && $preferences['smsNotifications'] ? 1 : 0;
    $newsletter = isset($preferences['newsletter']) && $preferences['newsletter'] ? 1 : 0;
    $profileVisible = isset($preferences['profileVisible']) && $preferences['profileVisible'] ? 1 : 0;
    $shareActivity = isset($preferences['shareActivity']) && $preferences['shareActivity'] ? 1 : 0;
    $dataSaving = isset($preferences['dataSaving']) && $preferences['dataSaving'] ? 1 : 0;
    
    // Bind parameters
    $update_stmt->bindParam(':user_id', $data['user_id']);
    $update_stmt->bindParam(':first_name', $data['firstName']);
    $update_stmt->bindParam(':last_name', $data['lastName']);
    $update_stmt->bindParam(':email', $data['email']);
    $update_stmt->bindParam(':phone', $data['phone']);
    $update_stmt->bindParam(':date_of_birth', $dateOfBirth);
    $update_stmt->bindParam(':address', $data['address']);
    $update_stmt->bindParam(':city', $data['city']);
    $update_stmt->bindParam(':state', $data['state']);
    $update_stmt->bindParam(':postal_code', $data['postalCode']);
    $update_stmt->bindParam(':gender', $gender);
    $update_stmt->bindParam(':email_notifications', $emailNotifications);
    $update_stmt->bindParam(':sms_notifications', $smsNotifications);
    $update_stmt->bindParam(':newsletter', $newsletter);
    $update_stmt->bindParam(':profile_visible', $profileVisible);
    $update_stmt->bindParam(':share_activity', $shareActivity);
    $update_stmt->bindParam(':data_saving', $dataSaving);
    
    // Execute update
    if ($update_stmt->execute()) {
        // Get updated user data for response
        $user_query = "SELECT id, first_name, last_name, email, phone, date_of_birth,
                              address, city, state, postal_code, gender,
                              email_notifications, sms_notifications, newsletter,
                              profile_visible, share_activity, data_saving, updated_at
                       FROM users WHERE id = :user_id";
        
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bindParam(':user_id', $data['user_id']);
        $user_stmt->execute();
        $updated_user = $user_stmt->fetch();
        
        // Convert boolean fields
        $updated_user['email_notifications'] = (bool)$updated_user['email_notifications'];
        $updated_user['sms_notifications'] = (bool)$updated_user['sms_notifications'];
        $updated_user['newsletter'] = (bool)$updated_user['newsletter'];
        $updated_user['profile_visible'] = (bool)$updated_user['profile_visible'];
        $updated_user['share_activity'] = (bool)$updated_user['share_activity'];
        $updated_user['data_saving'] = (bool)$updated_user['data_saving'];
        
        sendResponse(true, 'Profile updated successfully!', [
            'user' => $updated_user
        ], 200);
    } else {
        $errorInfo = $update_stmt->errorInfo();
        error_log("Database update error: " . json_encode($errorInfo));
        sendResponse(false, 'Failed to update profile. Please try again.', null, 500);
    }
    
} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    sendResponse(false, 'An error occurred during profile update: ' . $e->getMessage(), null, 500);
} catch (Error $e) {
    error_log("PHP Error: " . $e->getMessage());
    sendResponse(false, 'A system error occurred during profile update', null, 500);
}
?>