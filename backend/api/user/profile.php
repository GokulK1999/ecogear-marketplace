<?php
/**
 * User Profile API
 * Get user profile information
 */

require_once '../../config/cors.php';
require_once '../../config/database.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed', null, 405);
}

try {
    // Simple authentication check (get user_id from query parameter)
    $user_id = $_GET['user_id'] ?? null;
    
    if (!$user_id) {
        sendResponse(false, 'User ID is required', null, 400);
    }
    
    // Connect to database
    $conn = getDbConnection();
    
    // Get user profile
    $query = "SELECT id, first_name, last_name, email, phone, date_of_birth, 
                     address, city, state, postal_code, gender,
                     email_notifications, sms_notifications, newsletter,
                     profile_visible, share_activity, data_saving, created_at
              FROM users WHERE id = :user_id";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        sendResponse(false, 'User not found', null, 404);
    }
    
    $user = $stmt->fetch();
    
    // Convert boolean fields
    $user['email_notifications'] = (bool)$user['email_notifications'];
    $user['sms_notifications'] = (bool)$user['sms_notifications'];
    $user['newsletter'] = (bool)$user['newsletter'];
    $user['profile_visible'] = (bool)$user['profile_visible'];
    $user['share_activity'] = (bool)$user['share_activity'];
    $user['data_saving'] = (bool)$user['data_saving'];
    
    sendResponse(true, 'Profile retrieved successfully', [
        'user' => $user
    ]);
    
} catch (Exception $e) {
    error_log("Profile error: " . $e->getMessage());
    sendResponse(false, 'An error occurred while retrieving profile', null, 500);
}
?>