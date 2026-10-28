<?php
// Practical 9: PHP server-side checks + MySQLi prepared statements.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$course = $_POST['course'] ?? '';
$year = $_POST['year'] ?? '';
$gender = $_POST['gender'] ?? '';
$terms = isset($_POST['terms']);
$errors = [];

if (!$name || !$username || !$email || !$mobile || !$password || !$confirmPassword || !$course || !$year || !$gender) {
    $errors[] = 'Please fill in all required fields.';
}
if ($name !== '' && !preg_match('/^[A-Za-z][A-Za-z ]{2,}$/', $name)) {
    $errors[] = 'Enter a valid full name (at least 3 letters).';
}
if ($username !== '' && !preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
    $errors[] = 'Username must be 3 to 50 letters, numbers or underscores.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($mobile !== '' && !preg_match('/^[0-9]{10}$/', $mobile)) {
    $errors[] = 'Mobile number must have exactly 10 digits.';
}
if ($password !== '' && strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}
if (!in_array($course, ['CSE', 'IT', 'CE', 'ECE', 'ME'], true)) {
    $errors[] = 'Select a valid course.';
}
if (!in_array($year, ['1', '2', '3', '4'], true)) {
    $errors[] = 'Select a valid year.';
}
if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
    $errors[] = 'Select a valid gender.';
}
if (!$terms) {
    $errors[] = 'Accept the Terms and Conditions.';
}

if (!$errors) {
    require 'db.php';
    try {
        // A unique index on each column also prevents duplicates at database level.
        $check = $conn->prepare('SELECT email, username FROM students WHERE email = ? OR username = ?');
        $check->bind_param('ss', $email, $username);
        $check->execute();
        $check->bind_result($oldEmail, $oldUsername);
        while ($check->fetch()) {
            if (strcasecmp($oldEmail, $email) === 0) {
                $errors[] = 'This email is already registered.';
            }
            if ($oldUsername !== null && strcasecmp($oldUsername, $username) === 0) {
                $errors[] = 'This username is already taken.';
            }
        }
        $check->close();

        if (!$errors) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $yearNumber = (int) $year;
            $insert = $conn->prepare('INSERT INTO students (name, username, email, password_hash, mobile, course, year, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $insert->bind_param('ssssssis', $name, $username, $email, $hash, $mobile, $course, $yearNumber, $gender);
            $insert->execute();
            $insert->close();
            $success = true;
        }
    } catch (mysqli_sql_exception $e) {
        if ((int)$e->getCode() === 1062) {
            $errors[] = 'This email or username is already registered.';
        } else {
            error_log($e->getMessage());
            $errors[] = 'Registration could not be saved. Check the database setup.';
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub | Registration Result</title>
  <link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="../css/studenthub-unified.css">
</head>
<body class="hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header>
  <header class="site-header"><h1>🎓 StudentHub</h1><p>Student Registration</p></header>
  <main class="container result">
    <?php if (!empty($success)): ?>
      <h2 class="success">Registration Successful!</h2>
      <p>Your account has been created and saved in the StudentHub MySQL database.</p>
    <?php else: ?>
      <h2 class="error-heading">Registration Failed</h2>
      <?php foreach ($errors as $message): ?>
        <p class="error-item"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endforeach; ?>
    <?php endif; ?>
    <a class="back-link" href="register.html">Back to Registration</a>
  </main>
</body>
</html>
