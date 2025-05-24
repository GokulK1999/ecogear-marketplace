<?php
echo "<h1>🔍 Database Connection Diagnostic</h1>";

// Test different connection methods
$methods = [
    'localhost' => "mysql:host=localhost;dbname=ecogear_db;charset=utf8mb4",
    '127.0.0.1' => "mysql:host=127.0.0.1;dbname=ecogear_db;charset=utf8mb4", 
    'localhost:3306' => "mysql:host=localhost;port=3306;dbname=ecogear_db;charset=utf8mb4",
    '127.0.0.1:3306' => "mysql:host=127.0.0.1;port=3306;dbname=ecogear_db;charset=utf8mb4"
];

$username = 'root';
$password = '';

echo "<h2>Testing Different Connection Methods:</h2>";

foreach ($methods as $name => $dsn) {
    echo "<h3>Method: $name</h3>";
    echo "DSN: $dsn<br>";
    
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        echo "✅ <strong style='color: green;'>CONNECTION SUCCESSFUL!</strong><br>";
        
        // Test database query
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "✅ Users found: $count<br>";
        
        echo "<br>🎉 <strong>Use this connection method!</strong><br>";
        break; // Stop testing once we find a working method
        
    } catch (PDOException $e) {
        echo "❌ <span style='color: red;'>Failed: " . $e->getMessage() . "</span><br>";
    }
    
    echo "<hr>";
}

echo "<h2>System Information:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "<br>";
echo "Operating System: " . php_uname() . "<br>";
?>