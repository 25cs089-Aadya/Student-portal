<?php
session_start();
if (!isset($_SESSION["student_id"], $_SESSION["role"])) {
    header("Location: login.php?msg=login"); exit;
}
if (!isset($_SESSION["last_activity"]) || time() - $_SESSION["last_activity"] > 900) {
    header("Location: timeout.php"); exit;
}
if ($_SESSION["role"] !== "Admin") {
    http_response_code(403);
    echo '<h2>Unauthorized Access</h2><p>Only Admin users can open this page.</p><a href="dashboard.php">Go back</a>';
    exit;
}
$_SESSION["last_activity"] = time();
$name = htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentHub | Admin Dashboard</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>🎓 StudentHub</h1><p>Administration</p></header>
<main class="card"><h2>Admin Dashboard</h2><p>Welcome, <?= $name ?>!</p><p><b>Role:</b> Admin</p>
<section class="panel"><h3>Admin Panel</h3><p>You are logged in as an administrator.</p></section>
<a class="button-link" href="session_test.php">Session ID Test</a> <a class="button-link" href="logout.php">Logout</a>
</main></body></html>
