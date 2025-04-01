<?php
// Intentionally vulnerable login page for SQL injection testing
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "sqli_testing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL query - intentionally not using prepared statements
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        echo "Login successful!";
    } else {
        echo "Invalid credentials";
    }
    
    // Get client IP
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? 
          $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
          $_SERVER['REMOTE_ADDR'] ?? '';

    // Log the attempt (vulnerable version)
    $log_sql = "INSERT INTO attack_logs (username, password, attempt_time, ip_address) 
                VALUES ('$username', '$password', NOW(), '$ip')";
    $conn->query($log_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Test Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        input { padding: 8px; margin: 5px 0; width: 100%; }
        button { padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Login (Vulnerable to SQLi)</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>