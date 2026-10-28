<?php
require_once 'auth.php';
if ($_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    ?>
    <!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>403 Forbidden – StudentHub</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../css/studenthub-unified.css">
</head><body class="bg-light hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header><main class="container text-center py-5"><h1 class="display-1 text-danger">403</h1><h2>Access Forbidden</h2><p>Only Admin users can open this dashboard.</p><a class="btn btn-primary" href="student_dashboard.php">Back to Student Dashboard</a></main><p style="text-align:center;margin:24px;"><a href="../index.html">← Back to StudentHub Home</a></p>
</body></html>
    <?php exit;
}
require_once 'db.php';
require_once 'audit.php';
$errors = [];
$totalUsers = $totalStudents = $totalEvents = $totalRegistrations = 0;
$latestRegistrations = [];
try {
    // All login accounts are stored in students in your Practical 9/10 project.
    $totalUsers = (int)$conn->query('SELECT COUNT(*) FROM students WHERE password_hash IS NOT NULL')->fetch_row()[0];
    $totalStudents = (int)$conn->query("SELECT COUNT(*) FROM students WHERE role = 'Student'")->fetch_row()[0];
    $totalEvents = (int)$conn->query('SELECT COUNT(*) FROM events')->fetch_row()[0];
    $totalRegistrations = (int)$conn->query('SELECT COUNT(*) FROM registrations')->fetch_row()[0];
    $stmt = $conn->prepare('SELECT r.registration_id, s.name AS student_name, e.event_name, r.registration_date, r.status FROM registrations r LEFT JOIN students s ON r.student_id = s.student_id LEFT JOIN events e ON r.event_id = e.event_id ORDER BY r.registration_date DESC, r.registration_id DESC LIMIT 5');
    $stmt->execute();
    $latestRegistrations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Throwable $error) {
    error_log('Dashboard query: '.$error->getMessage());
    $errors[] = 'Could not load some database statistics. Check the tables in phpMyAdmin.';
}
writeAuditLog($conn, 'ADMIN_DASHBOARD_VIEW', 'Admin opened the Practical 14 dashboard.');
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentHub | Admin Dashboard – Practical 14</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="../css/studenthub-unified.css">
</head>
<body class="hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header><div class="container-fluid"><div class="row"><aside class="col-md-3 col-lg-2 sidebar p-3"><h2 class="text-white h4">🎓 StudentHub</h2><p class="text-white-50">Admin Menu</p><a class="active" href="admin_dashboard.php">Dashboard</a><a href="../prac%2011/manage_students.php">Manage Students</a><a href="../prac%2012/events.php">Manage Events</a><a href="../prac%2013/index.html">Fetch API Events</a><a href="../prac%2010/logout.php">Logout</a></aside><main class="col-md-9 col-lg-10 p-4"><h1 class="top-title">Admin Dashboard</h1><p>Welcome, <?= h($_SESSION['name'] ?? 'Admin') ?>! &nbsp; <strong>Role:</strong> Admin</p>
<?php foreach($errors as $error): ?><div class="alert alert-warning"><?= h($error) ?></div><?php endforeach; ?>
<div class="row g-3 my-3">
<?php foreach ([['Registered Users',$totalUsers],['Students',$totalStudents],['Events',$totalEvents],['Event Registrations',$totalRegistrations]] as $item): ?>
<div class="col-6 col-xl-3"><div class="card stat-card p-3 h-100"><div class="text-muted"><?= h($item[0]) ?></div><div class="stat-number"><?= (int)$item[1] ?></div></div></div>
<?php endforeach; ?></div>
<section class="card panel p-3"><h2 class="h4">Latest Event Registrations</h2><div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>ID</th><th>Student</th><th>Event</th><th>Date</th><th>Status</th></tr></thead><tbody>
<?php if (!$latestRegistrations): ?><tr><td colspan="5">No event registrations found.</td></tr><?php endif; ?>
<?php foreach ($latestRegistrations as $r): ?><tr><td><?= (int)$r['registration_id'] ?></td><td><?= h($r['student_name'] ?? 'Unknown') ?></td><td><?= h($r['event_name'] ?? 'Unknown') ?></td><td><?= h($r['registration_date']) ?></td><td><?= h($r['status']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></section><p class="mt-3 small text-muted">Counts and latest registrations are fetched from MySQL. Admin dashboard visits are saved to audit_logs.</p></main></div></div><p style="text-align:center;margin:24px;"><a href="../index.html">← Back to StudentHub Home</a></p>
</body></html>
