<?php
echo "<h2>🔍 Password Debug Test</h2>";

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=ecogear_db;charset=utf8mb4", 'root', '');
    
    // Get the demo user
    $stmt = $pdo->query("SELECT email, password_hash FROM users WHERE email = 'gokul@ecogear.com'");
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p><strong>User found:</strong> {$user['email']}</p>";
        echo "<p><strong>Stored hash:</strong> {$user['password_hash']}</p><br>";
        
        // Test different passwords
        $test_passwords = ['password', 'EcoDemo123', 'demo123', 'ecogear'];
        
        echo "<h3>Testing Common Passwords:</h3>";
        foreach ($test_passwords as $pwd) {
            $is_valid = password_verify($pwd, $user['password_hash']);
            $status = $is_valid ? '✅' : '❌';
            echo "<p>$status Password '$pwd': " . ($is_valid ? 'CORRECT' : 'Wrong') . "</p>";
        }
        
    } else {
        echo "<p>❌ No user found with email gokul@ecogear.com</p>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>