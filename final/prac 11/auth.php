<?php
// Share the normal PHP session created by Practical 10.
// Practical 10 stores the logged-in student ID as "student_id".
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['student_id'], $_SESSION['role'])) {
    header('Location: ../prac%2010/login.php?msg=login');
    exit;
}

// Same 15-minute session timeout used in Practical 10.
if (!isset($_SESSION['last_activity']) || time() - $_SESSION['last_activity'] > 900) {
    header('Location: ../prac%2010/timeout.php');
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    echo '<h2>Unauthorized Access</h2><p>Only Admin users can manage students.</p>';
    echo '<a href="../prac%2010/dashboard.php">Back to Dashboard</a>';
    exit;
}

$_SESSION['last_activity'] = time();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function check_token() {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Invalid form request. Refresh and try again.');
    }
}
