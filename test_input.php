<?php
require_once 'security.php';

// Enable site-wide protection for demonstration
check_all_inputs();

// Simulate database query
if (isset($_GET['query'])) {
    $input = $_GET['query'];
    echo "<div class='p-4 mb-4 bg-white rounded shadow'>";
    echo "<h2 class='text-xl font-semibold mb-2'>Test Results</h2>";
    
    // Show raw input (for educational purposes)
    echo "<p><strong>Your input:</strong> <code>" . htmlspecialchars($input) . "</code></p>";
    
    // Show detection result
    if (detect_sqli($input)) {
        echo "<p class='text-red-600 font-bold'>🔥 SQL Injection detected and blocked!</p>";
    } else {
        echo "<p class='text-green-600 font-bold'>✅ Input appears safe</p>";
        echo "<p class='mt-2 text-sm'>Note: This is just a simulation. In a real application, ";
        echo "you should still use prepared statements even if no SQLi is detected.</p>";
    }
    
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Test Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">SQLi Input Test Results</h1>
        
        <?php if (!isset($_GET['query'])): ?>
            <div class="bg-white p-6 rounded-lg shadow">
                <p>No test query provided. Go back to the <a href="test_sqli.php" class="text-blue-500">test center</a>.</p>
            </div>
        <?php endif; ?>
        
        <div class="mt-6 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">How the Protection Works</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>All GET/POST/COOKIE inputs are automatically scanned</li>
                <li>Uses regex patterns to detect common SQLi techniques</li>
                <li>Logs attempts to error_log for monitoring</li>
                <li>Returns HTTP 403 Forbidden when attacks are detected</li>
            </ul>
            <p class="mt-4">Try these in the test center:
                <code class="block bg-gray-100 p-2 mt-2 rounded">1' UNION SELECT 1,2,3 --</code>
                <code class="block bg-gray-100 p-2 mt-2 rounded">' OR 1=1 --</code>
            </p>
        </div>
    </div>
</body>
</html>