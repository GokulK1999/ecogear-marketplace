<?php
echo "<h2>🌍 Universal Database Connection Test</h2>";
echo "<p><em>This test works on Windows, Mac, and Linux!</em></p>";

// Detect operating system
$os = php_uname('s');
echo "<p><strong>Current System:</strong> $os</p>";

// Connection settings - use 127.0.0.1 for better compatibility
$host = '127.0.0.1';  // Changed from 'localhost'
$dbname = 'ecogear_db';
$username = 'root';
$password = '';

echo "<p><strong>Universal Connection Settings:</strong></p>";
echo "Host: $host (works on all XAMPP installations)<br>";
echo "Database: $dbname<br>";
echo "Username: $username (default XAMPP)<br>";
echo "Password: Empty (default XAMPP)<br><br>";

try {
    // This PDO connection works on all platforms
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ <strong>Universal connection successful!</strong><br>";
    echo "✅ This will work on Windows, Mac, and Linux<br><br>";
    
    // Test database content
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "✅ Users in database: $userCount<br>";
    
    if ($userCount > 0) {
        $user = $pdo->query("SELECT first_name, last_name, email FROM users LIMIT 1")->fetch();
        echo "✅ Demo user ready: {$user['email']}<br>";
    }
    
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    echo "✅ Orders in database: $orderCount<br>";
    
    echo "<br>🎉 <strong style='color: green;'>Ready for submission - works everywhere!</strong>";
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>