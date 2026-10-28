<?php
// Practical 7: PHP form validation and CSV file storage
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

// Read the submitted fields
$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$course = trim($_POST['course'] ?? '');
$year = trim($_POST['year'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$terms = isset($_POST['terms']);
$errors = [];

// Validate on the server (HTML required fields can be bypassed)
if (!preg_match('/^[a-zA-Z ]{3,60}$/', $name)) {
    $errors[] = 'Enter a valid full name (at least 3 letters).';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}
if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    $errors[] = 'Mobile number must contain exactly 10 digits.';
}
if (strlen($password) < 8) {
    $errors[] = 'Password must have at least 8 characters.';
}
if ($password !== $confirmPassword) {
    $errors[] = 'Password and Confirm Password do not match.';
}
if (!in_array($course, ['CSE', 'IT', 'CE', 'ECE', 'ME'], true)) {
    $errors[] = 'Please select a valid course.';
}
if (!in_array($year, ['1', '2', '3', '4'], true)) {
    $errors[] = 'Please select a valid year.';
}
if (!in_array($gender, ['Male', 'Female', 'Other'], true)) {
    $errors[] = 'Please select a gender.';
}
if (!$terms) {
    $errors[] = 'Please accept the Terms and Conditions.';
}

// Save only if all validation checks pass
if (empty($errors)) {
    $folder = __DIR__ . '/data';
    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
        $errors[] = 'Could not create the data folder.';
    } else {
        $file = $folder . '/registrations.csv';
        $handle = fopen($file, 'a+');
        if ($handle === false) {
            $errors[] = 'Could not save registration. Check folder permissions.';
        } else {
            if (flock($handle, LOCK_EX)) {
                // The header is written once when the file is empty.
                if (fstat($handle)['size'] === 0) {
                    fputcsv($handle, ['Name', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'Terms Accepted']);
                }
                // Passwords are not saved in plain text or in this CSV.
                // Prefix spreadsheet formula characters to keep the CSV safe to open.
                $safeCell = function ($value) {
                    return preg_match('/^[=+@\-\t\r]/', $value) ? "'" . $value : $value;
                };
                $saved = fputcsv($handle, [
                    $safeCell($name), $safeCell($email), $safeCell($mobile),
                    $course, $year, $gender, 'Yes'
                ]);
                if ($saved === false) {
                    $errors[] = 'Could not write registration to CSV.';
                }
                flock($handle, LOCK_UN);
            } else {
                $errors[] = 'Could not lock the CSV file for writing.';
            }
            fclose($handle);
        }
    }
}
$success = empty($errors);
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
  <header><h1>StudentHub</h1><p>Student Portal | Practical 7</p></header>
  <main class="container message">
    <?php if ($success): ?>
      <h2>Registration Successful!</h2>
      <p>Your details were submitted using the POST method.</p>
      <p>Your information has been saved in <strong>data/registrations.csv</strong>.</p>
    <?php else: ?>
      <h2 class="fail">Registration Failed</h2>
      <p>Please correct these errors:</p>
      <ul class="errors">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <a class="back" href="register.html">Back to Registration</a>
  </main>
</body>
</html>
