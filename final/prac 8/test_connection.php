<?php
require_once "db.php";

// Prepared statement: display students from the chosen year onwards.
$year = 1;
$sql = "SELECT student_id, name, email, course, year FROM students WHERE year >= ? ORDER BY student_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$year]);
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub | Database Connection</title>
    <link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="../css/studenthub-unified.css">
</head>
<body class="hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header>
    <main class="container">
        <h1>🎓 StudentHub</h1>
        <p class="subtitle">Practical 8 - Database Connection Test</p>
        <div class="success" role="status">✓ StudentHub Database Connection Successful!</div>

        <h2>Students Table Output</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$student["student_id"]) ?></td>
                        <td><?= htmlspecialchars($student["name"]) ?></td>
                        <td><?= htmlspecialchars($student["email"]) ?></td>
                        <td><?= htmlspecialchars($student["course"]) ?></td>
                        <td><?= htmlspecialchars((string)$student["year"]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($students) === 0): ?>
                    <tr><td colspan="5">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p class="note">Student records are fetched from MySQL using PHP PDO and a prepared statement.</p>
    </main>
</body>
</html>
