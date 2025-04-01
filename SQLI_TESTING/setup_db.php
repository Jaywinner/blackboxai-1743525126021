<?php
// Database setup for SQL injection testing environment
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS sqli_testing");
$conn->select_db("sqli_testing");

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    )",
    
    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )",
    
    "CREATE TABLE IF NOT EXISTS attack_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        attempt_time DATETIME NOT NULL,
        ip_address VARCHAR(45) NOT NULL
    )",
    
    "CREATE TABLE IF NOT EXISTS blocked_ips (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL UNIQUE,
        blocked_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
}

// Insert initial admin (password: Admin@123)
$hashed_password = password_hash('Admin@123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$hashed_password')");

// Insert test users
$test_users = [
    ['user1', password_hash('password1', PASSWORD_DEFAULT)],
    ['user2', password_hash('password2', PASSWORD_DEFAULT)],
    ['user3', password_hash('password3', PASSWORD_DEFAULT)]
];

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
foreach ($test_users as $user) {
    $stmt->bind_param("ss", $user[0], $user[1]);
    $stmt->execute();
}

echo "Database setup completed successfully!";
echo "<br>Admin credentials: admin / Admin@123";
echo "<br>Test users: user1/password1, user2/password2, user3/password3";
?>