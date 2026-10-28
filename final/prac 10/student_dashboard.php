<?php
session_start();
if (!isset($_SESSION["student_id"], $_SESSION["role"])) {
    header("Location: login.php?msg=login"); exit;
}
if (!isset($_SESSION["last_activity"]) || time() - $_SESSION["last_activity"] > 900) {
    header("Location: timeout.php"); exit;
}
if ($_SESSION["role"] !== "Student") {
    http_response_code(403);
    echo '<h2>Unauthorized Access</h2><p>Only Student users can open this page.</p><a href="dashboard.php">Go back</a>';
    exit;
}
$_SESSION["last_activity"] = time();
$name = htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentHub | Student Dashboard</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="../css/studenthub-unified.css">
</head><body class="hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header>
<header><h1>🎓 StudentHub</h1><p>Student Portal</p></header>
<main class="card"><h2>Student Dashboard</h2><p>Welcome, <?= $name ?>!</p><p><b>Role:</b> Student</p>
<section class="panel"><h3>Student Portal</h3><p>You have successfully logged into your StudentHub account.</p></section>
<a class="button-link" href="session_test.php">Session ID Test</a> <a class="button-link" href="logout.php">Logout</a>
</main><p style="text-align:center;margin:24px;"><a href="../index.html">← Back to StudentHub Home</a></p>
</body></html>
