<?php
session_start();
require_once '../security.php';

// Admin authentication
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "sqli_testing");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process admin actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $id = sanitize_input($_POST['id']);
        $conn->query("DELETE FROM attack_logs WHERE id = $id");
    } elseif (isset($_POST['block_ip'])) {
        $ip = sanitize_input($_POST['ip']);
        $conn->query("INSERT INTO blocked_ips (ip_address) VALUES ('$ip')");
    }
}

// Get all attack logs
$logs = $conn->query("SELECT * FROM attack_logs ORDER BY attempt_time DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>SQL Injection Attack Monitor</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .danger { background-color: #ffdddd; }
        form { display: inline; }
    </style>
</head>
<body>
    <h1>SQL Injection Attack Monitor</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username Attempt</th>
            <th>Password Attempt</th>
            <th>Time</th>
            <th>IP Address</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $logs->fetch_assoc()): ?>
        <tr class="<?= detect_sqli($row['username']) ? 'danger' : '' ?>">
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['password']) ?></td>
            <td><?= htmlspecialchars($row['attempt_time']) ?></td>
            <td><?= htmlspecialchars($row['ip_address']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
                <form method="POST">
                    <input type="hidden" name="ip" value="<?= $row['ip_address'] ?>">
                    <button type="submit" name="block_ip">Block IP</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>