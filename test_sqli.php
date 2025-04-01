<?php
require_once 'security.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>SQLi Test Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">SQL Injection Test Center</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Login Form Test -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Login Form Test</h2>
                <form action="login.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1">Username</label>
                        <input type="text" name="username" class="w-full p-2 border rounded" 
                               value="admin' --">
                    </div>
                    <div>
                        <label class="block mb-1">Password</label>
                        <input type="text" name="password" class="w-full p-2 border rounded" 
                               value="password">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Test Login
                    </button>
                </form>
            </div>

            <!-- Direct Input Test -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Direct Input Test</h2>
                <form action="test_input.php" method="GET" class="space-y-4">
                    <div>
                        <label class="block mb-1">Test Input</label>
                        <input type="text" name="query" class="w-full p-2 border rounded" 
                               value="1' UNION SELECT username, password FROM users --">
                    </div>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Test Input
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Common SQLi Payloads</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium mb-2">Bypass Login</h3>
                    <code class="text-sm bg-gray-200 p-1 rounded">admin' --</code><br>
                    <code class="text-sm bg-gray-200 p-1 rounded">' OR 1=1 --</code>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium mb-2">Data Extraction</h3>
                    <code class="text-sm bg-gray-200 p-1 rounded">' UNION SELECT 1,2,3 --</code><br>
                    <code class="text-sm bg-gray-200 p-1 rounded">' UNION SELECT table_name,2,3 FROM information_schema.tables --</code>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium mb-2">Database Modification</h3>
                    <code class="text-sm bg-gray-200 p-1 rounded">'; DROP TABLE users --</code><br>
                    <code class="text-sm bg-gray-200 p-1 rounded">'; UPDATE users SET password = 'hacked' WHERE username = 'admin' --</code>
                </div>
            </div>
        </div>
    </div>
</body>
</html>