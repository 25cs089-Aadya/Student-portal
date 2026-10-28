<?php
// Require this at the beginning of each protected page.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Practical 10 saves student_id (not user_id) after successful login.
if (!isset($_SESSION["student_id"], $_SESSION["role"])) {
    header("Location: /prac%2010/login.php?error=unauthorized");
    exit;
}

if (!isset($_SESSION["last_activity"]) || time() - $_SESSION["last_activity"] > 900) {
    $_SESSION = [];
    session_destroy();
    header("Location: /prac%2010/login.php?error=timeout");
    exit;
}

if ($_SESSION["role"] !== "Admin") {
    // Do not show the admin pages to students.
    header("Location: /prac%2010/student_dashboard.php?error=unauthorized");
    exit;
}

$_SESSION["last_activity"] = time();

if (empty($_SESSION["event_token"])) {
    $_SESSION["event_token"] = bin2hex(random_bytes(16));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
