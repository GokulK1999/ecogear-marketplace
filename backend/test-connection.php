<?php
require_once 'config/cors.php';
require_once 'config/database.php';

try {
    echo "Testing database connection...\n";
    $conn = getDbConnection();
    echo "✅ Database connection successful!\n";
    
    // Test if users table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists!\n";
    } else {
        echo "❌ Users table does not exist!\n";
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Database test completed'
    ]);
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>