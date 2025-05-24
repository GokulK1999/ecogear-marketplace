<?php
echo "<h1>🚀 EcoGear Backend Server</h1>";
echo "<p>✅ Server is running successfully!</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

echo "<h2>Available Test Files:</h2>";
echo "<ul>";
echo "<li><a href='mariadb-test.php'>🔍 Database Connection Test</a></li>";
echo "<li><a href='api/auth/register.php'>📝 Registration API (POST)</a></li>";
echo "<li><a href='api/auth/login.php'>🔐 Login API (POST)</a></li>";
echo "</ul>";

echo "<h2>Database Status:</h2>";
try {
    // Use working connection method
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ecogear_db;charset=utf8mb4", 'root', '');
    echo "✅ <strong style='color: green;'>Database connection: SUCCESS</strong><br>";
    
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    echo "✅ Users in database: $userCount<br>";
    
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    echo "✅ Orders in database: $orderCount<br>";
    
    // Show demo user
    $demoUser = $pdo->query("SELECT email FROM users WHERE email = 'demo@ecogear.com'")->fetch();
    if ($demoUser) {
        echo "✅ Demo login available: <strong>demo@ecogear.com / password</strong><br>";
    }
    
    echo "<br>🎉 <strong style='color: green;'>Backend is fully operational!</strong>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>