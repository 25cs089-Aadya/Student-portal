<?php
session_start();
// Shows only PASS/FAIL, not secret session IDs.
$loggedIn = isset($_SESSION["student_id"]);
$changed = !empty($_SESSION["id_changed"]);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentHub | Session Test</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>🎓 StudentHub</h1><p>Session ID Regeneration Test</p></header>
<main class="card"><h2>Session Test</h2>
<?php if ($loggedIn): ?>
<p><b>Login status:</b> Logged in</p>
<p><b>Session ID regeneration:</b> <?= $changed ? "PASS – Session ID changed after login." : "Not confirmed" ?></p>
<a class="button-link" href="dashboard.php">Dashboard</a>
<?php else: ?>
<p><b>Login status:</b> Not logged in</p><p>Go to Login and sign in, then return to this page.</p>
<a class="button-link" href="login.php">Login</a>
<?php endif; ?>
</main></body></html>
