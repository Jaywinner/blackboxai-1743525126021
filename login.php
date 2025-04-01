<?php
require_once 'security.php';

// Site-wide protection (optional - uncomment to enable)
// check_all_inputs();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Method 1: Standalone detection (just for login)
    if (detect_sqli($username) || detect_sqli($password)) {
        die("⚠️ SQL injection attempt detected in login form!");
    }

    // Method 2: Secure database query
    $conn = new mysqli("localhost", "root", "", "test_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            echo "✅ Login successful! Welcome " . htmlspecialchars($user['username']);
        } else {
            echo "❌ Invalid password";
        }
    } else {
        echo "❌ User not found";
    }
    $stmt->close();
    $conn->close();
}
?>