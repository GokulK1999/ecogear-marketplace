<?php
/**
 * Profile Image Upload API
 * Handles profile picture uploads
 */

// Turn off HTML error display for clean JSON responses
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once '../../config/cors.php';
require_once '../../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

try {
    // Check if user_id is provided
    if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
        sendResponse(false, 'User ID is required', null, 400);
    }
    
    $user_id = sanitizeInput($_POST['user_id']);
    
    // Check if file was uploaded
    if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(false, 'No image file uploaded or upload error occurred', null, 400);
    }
    
    $uploadedFile = $_FILES['profile_image'];
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $uploadedFile['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        sendResponse(false, 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.', null, 400);
    }
    
    // Validate file size (5MB max)
    $maxSize = 5 * 1024 * 1024; // 5MB in bytes
    if ($uploadedFile['size'] > $maxSize) {
        sendResponse(false, 'File size too large. Maximum size is 5MB.', null, 400);
    }
    
    // Generate unique filename
    $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    $fileName = 'profile_' . $user_id . '_' . time() . '.' . $fileExtension;
    
    // Create upload directory if it doesn't exist
    $uploadDir = '../../uploads/profiles/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $uploadPath = $uploadDir . $fileName;
    
    // Move uploaded file
    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
        
        // Update database with new profile picture path
        $conn = getDbConnection();
        
        // Get current profile picture to delete old one
        $current_query = "SELECT profile_picture FROM users WHERE id = :user_id";
        $current_stmt = $conn->prepare($current_query);
        $current_stmt->bindParam(':user_id', $user_id);
        $current_stmt->execute();
        $current_user = $current_stmt->fetch();
        
        // Delete old profile picture if it exists
        if ($current_user && !empty($current_user['profile_picture'])) {
            $oldImagePath = '../../uploads/profiles/' . basename($current_user['profile_picture']);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        // Update database with new image path
        $imagePath = '/uploads/profiles/' . $fileName;
        $update_query = "UPDATE users SET profile_picture = :profile_picture, updated_at = CURRENT_TIMESTAMP WHERE id = :user_id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':profile_picture', $imagePath);
        $update_stmt->bindParam(':user_id', $user_id);
        
        if ($update_stmt->execute()) {
            sendResponse(true, 'Profile picture uploaded successfully!', [
                'image_path' => $imagePath,
                'file_name' => $fileName
            ], 200);
        } else {
            // If database update fails, delete the uploaded file
            unlink($uploadPath);
            sendResponse(false, 'Failed to update profile picture in database', null, 500);
        }
        
    } else {
        sendResponse(false, 'Failed to upload image file', null, 500);
    }
    
} catch (Exception $e) {
    error_log("Image upload error: " . $e->getMessage());
    sendResponse(false, 'An error occurred during image upload: ' . $e->getMessage(), null, 500);
}
?>