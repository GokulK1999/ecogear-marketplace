<?php
require_once 'config/database.php';

try {
    $conn = getDbConnection();
    echo "✅ Database connection successful!<br>";
    
    $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM users");
    $stmt->execute();
    $result = $stmt->fetch();
    
    echo "✅ Sample users in database: " . $result['user_count'] . "<br>";
    echo "✅ Backend is working perfectly!";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>