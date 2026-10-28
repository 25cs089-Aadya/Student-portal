<?php
session_start();
// A new visitor can check the session ID change using session_test.php first.
if (isset($_SESSION["student_id"], $_SESSION["role"])) {
    if (isset($_SESSION["last_activity"]) && time() - $_SESSION["last_activity"] <= 900) {
        header("Location: dashboard.php");
        exit;
    }
    header("Location: timeout.php");
    exit;
}
$messages = [
    "invalid" => "Invalid email or password.",
    "empty" => "Please enter both email and password.",
    "logout" => "You have logged out successfully.",
    "timeout" => "Your session expired. Please log in again.",
    "login" => "Please log in to view that page."
];
$msg = $messages[$_GET["msg"] ?? ""] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>StudentHub | Login</title><link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>🎓 StudentHub</h1><p>Student Portal • Practical 10</p></header>
<main class="card small">
<h2>Login</h2>
<p>Sign in with the email and password from Practical 9.</p>
<?php if ($msg !== ""): ?><p class="message" role="alert"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<form action="authenticate.php" method="post">
<label for="email">Email</label>
<input type="email" name="email" id="email" placeholder="Enter email" required>
<label for="password">Password</label>
<input type="password" name="password" id="password" placeholder="Enter password" required>
<button type="submit">Login</button>
</form>
<p class="help"><a href="session_test.php">Session ID test</a></p>
</main>
</body></html>
