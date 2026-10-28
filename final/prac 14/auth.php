<?php
// Uses the session created by Practical 10 (student_id, role, name).
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['student_id'], $_SESSION['role'])) {
    header('Location: ../prac%2010/login.php?msg=login');
    exit;
}
if (!isset($_SESSION['last_activity']) || time() - $_SESSION['last_activity'] > 900) {
    header('Location: ../prac%2010/timeout.php');
    exit;
}
$_SESSION['last_activity'] = time();
function h($value) { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8'); }
