<?php
/**
 * User Profile Update API
 * Update user profile information
 */

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
    
    // Sanitize input
    $data = sanitizeInput($input);
    
    // Required fields
    if (!isset($data['user_id']) || empty($data['user_id'])) {
        sendResponse(false, 'User ID is required', null, 400);
    }
    
    // Connect to database
    $conn = getDbConnection();
    
    // Update user profile
    $query = "UPDATE users SET 
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                date_of_birth = :date_of_birth,
                gender = :gender,
                address = :address,
                city = :city,
                state = :state,
                postal_code = :postal_code,
                email_notifications = :email_notifications,
                sms_notifications = :sms_notifications,
                newsletter = :newsletter,
                profile_visible = :profile_visible,
                share_activity = :share_activity,
                data_saving = :data_saving,
                updated_at = CURRENT_TIMESTAMP
              WHERE id = :user_id";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':user_id', $data['user_id']);
    $stmt->bindParam(':first_name', $data['firstName']);
    $stmt->bindParam(':last_name', $data['lastName']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':date_of_birth', $data['dateOfBirth']);
    $stmt->bindParam(':gender', $data['gender']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':city', $data['city']);
    $stmt->bindParam(':state', $data['state']);
    $stmt->bindParam(':postal_code', $data['postalCode']);
    
    // Handle boolean preferences
    $email_notifications = isset($data['preferences']['emailNotifications']) && $data['preferences']['emailNotifications'] ? 1 : 0;
    $sms_notifications = isset($data['preferences']['smsNotifications']) && $data['preferences']['smsNotifications'] ? 1 : 0;
    $newsletter = isset($data['preferences']['newsletter']) && $data['preferences']['newsletter'] ? 1 : 0;
    $profile_visible = isset($data['preferences']['profileVisible']) && $data['preferences']['profileVisible'] ? 1 : 0;
    $share_activity = isset($data['preferences']['shareActivity']) && $data['preferences']['shareActivity'] ? 1 : 0;
    $data_saving = isset($data['preferences']['dataSaving']) && $data['preferences']['dataSaving'] ? 1 : 0;
    
    $stmt->bindParam(':email_notifications', $email_notifications);
    $stmt->bindParam(':sms_notifications', $sms_notifications);
    $stmt->bindParam(':newsletter', $newsletter);
    $stmt->bindParam(':profile_visible', $profile_visible);
    $stmt->bindParam(':share_activity', $share_activity);
    $stmt->bindParam(':data_saving', $data_saving);
    
    // Execute update
    if ($stmt->execute()) {
        sendResponse(true, 'Profile updated successfully', [
            'updated_fields' => count($data)
        ]);
    } else {
        sendResponse(false, 'Failed to update profile', null, 500);
    }
    
} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    sendResponse(false, 'An error occurred while updating profile', null, 500);
}
?>