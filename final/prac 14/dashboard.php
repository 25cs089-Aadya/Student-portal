<?php
require_once 'auth.php';
if ($_SESSION['role'] === 'Admin') {
    header('Location: admin_dashboard.php');
} elseif ($_SESSION['role'] === 'Student') {
    header('Location: student_dashboard.php');
} else {
    http_response_code(403);
    exit('403 Forbidden');
}
exit;
