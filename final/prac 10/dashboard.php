<?php
session_start();
if (!isset($_SESSION["student_id"], $_SESSION["role"])) {
    header("Location: login.php?msg=login"); exit;
}
// 15 minutes of inactivity
if (!isset($_SESSION["last_activity"]) || time() - $_SESSION["last_activity"] > 900) {
    header("Location: timeout.php"); exit;
}
$_SESSION["last_activity"] = time();
if ($_SESSION["role"] === "Admin") {
    header("Location: admin_dashboard.php");
} elseif ($_SESSION["role"] === "Student") {
    header("Location: student_dashboard.php");
} else {
    header("Location: logout.php");
}
exit;
?>
