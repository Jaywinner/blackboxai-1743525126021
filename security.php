<?php
/**
 * SQL Injection Protection Functions
 */
 
function detect_sqli($input) {
    // Enhanced pattern matching
    $patterns = [
        '/\b(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|CREATE|EXEC)\b/i',
        '/--|\#|\/\*|;/',
        '/\b(AND|OR)\s+[\d\w]+\s*=\s*[\d\w]+\b/i',
        '/\b(WAITFOR|DELAY)\b.*\b(SECOND|MINUTE)\b/i',
        '/\b(SLEEP|BENCHMARK)\b.*\(/i'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            error_log("SQLi detected: " . substr($input, 0, 100));
            return true;
        }
    }
    return false;
}

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function check_all_inputs() {
    $inputs = array_merge($_GET, $_POST, $_COOKIE);
    foreach ($inputs as $key => $value) {
        if (detect_sqli($value)) {
            http_response_code(403);
            die("Security violation detected. Request blocked.");
        }
    }
}