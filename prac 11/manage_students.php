<?php
require_once 'auth.php';
require_once 'db.php';

// Add a student using a POST request.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    check_token();
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    $course = $_POST['course'] ?? '';
    $year = (int)($_POST['year'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    if (strlen($name) < 3 || !preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)
        || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10}$/', $mobile)
        || strlen($password) < 8 || !in_array($course, ['CSE','IT','CE','ECE','ME'], true)
        || $year < 1 || $year > 4 || !in_array($gender, ['Male','Female','Other'], true)) {
        header('Location: manage_students.php?error=invalid'); exit;
    }
    $check = $conn->prepare('SELECT student_id FROM students WHERE email = ? OR username = ? LIMIT 1');
    $check->bind_param('ss', $email, $username);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();
    if ($exists) { header('Location: manage_students.php?error=duplicate'); exit; }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'Student';
    $stmt = $conn->prepare('INSERT INTO students (name,username,email,mobile,password_hash,course,year,gender,role,status) VALUES (?,?,?,?,?,?,?,?,?,"Active")');
    $stmt->bind_param('ssssssiss', $name, $username, $email, $mobile, $hash, $course, $year, $gender, $role);
    try {
        $stmt->execute();
        header('Location: manage_students.php?message=added');
    } catch (mysqli_sql_exception $ex) {
        header('Location: manage_students.php?error=' . ($ex->getCode() == 1062 ? 'duplicate' : 'failed'));
    }
    exit;
}

$search = trim($_GET['search'] ?? '');
$course = $_GET['course'] ?? '';
$status = $_GET['status'] ?? 'Active';
if (!in_array($course, ['', 'CSE','IT','CE','ECE','ME'], true)) $course = '';
if (!in_array($status, ['All','Active','Inactive'], true)) $status = 'Active';
$sql = "SELECT student_id,name,username,email,mobile,course,year,gender,status FROM students WHERE role = 'Student'";
$parameters = []; $types = '';
if ($search !== '') {
    $sql .= ' AND (name LIKE ? OR email LIKE ? OR username LIKE ?)';
    $term = '%' . $search . '%';
    $parameters = [$term, $term, $term]; $types .= 'sss';
}
if ($course !== '') { $sql .= ' AND course = ?'; $parameters[] = $course; $types .= 's'; }
if ($status !== 'All') { $sql .= ' AND status = ?'; $parameters[] = $status; $types .= 's'; }
$sql .= ' ORDER BY student_id DESC';
$stmt = $conn->prepare($sql);
if ($parameters) $stmt->bind_param($types, ...$parameters);
$stmt->execute();
$students = $stmt->get_result();
$messages = ['added'=>'Student added successfully!', 'updated'=>'Student details updated successfully!', 'deleted'=>'Student status changed to Inactive successfully!'];
$errors = ['invalid'=>'Please enter valid details in all fields.', 'duplicate'=>'Email or username already exists.', 'failed'=>'Operation failed. Please try again.', 'notfound'=>'Student not found.'];
$message = $messages[$_GET['message'] ?? ''] ?? '';
$error = $errors[$_GET['error'] ?? ''] ?? '';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>StudentHub | Manage Students</title><link rel="stylesheet" href="style.css"></head><body>
<header><h1>🎓 StudentHub</h1><p>Practical 11 - Student Management</p></header>
<main class="container">
<nav><a href="../prac%2010/admin_dashboard.php">Admin Dashboard</a> · <a href="../prac%2010/logout.php">Logout</a></nav>
<?php if ($message): ?><p class="success" role="status"><?= e($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?>
<section><h2>Add New Student</h2><form method="post" class="form-grid">
<input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
<label>Full Name<input name="name" required minlength="3"></label>
<label>Username<input name="username" required minlength="3" pattern="[A-Za-z0-9_]+"></label>
<label>Email<input name="email" type="email" required></label>
<label>Mobile<input name="mobile" type="tel" pattern="[0-9]{10}" maxlength="10" required></label>
<label>Initial Password<input name="password" type="password" minlength="8" required></label>
<label>Course<select name="course" required><option value="">Select</option><?php foreach (['CSE','IT','CE','ECE','ME'] as $item): ?><option><?= $item ?></option><?php endforeach; ?></select></label>
<label>Year<select name="year" required><option value="">Select</option><?php for($y=1;$y<=4;$y++): ?><option value="<?= $y ?>"><?= $y ?></option><?php endfor; ?></select></label>
<label>Gender<select name="gender" required><option value="">Select</option><?php foreach(['Male','Female','Other'] as $item): ?><option><?= $item ?></option><?php endforeach; ?></select></label>
<button type="submit" name="add_student" value="1">Add Student</button></form></section>
<section><h2>Student Records</h2><form method="get" class="filters">
<label>Search<input type="search" name="search" placeholder="Name, email or username" value="<?= e($search) ?>"></label>
<label>Course<select name="course"><option value="">All courses</option><?php foreach(['CSE','IT','CE','ECE','ME'] as $item): ?><option value="<?= $item ?>" <?= $course===$item?'selected':'' ?>><?= $item ?></option><?php endforeach; ?></select></label>
<label>Status<select name="status"><?php foreach(['Active','Inactive','All'] as $item): ?><option <?= $status===$item?'selected':'' ?>><?= $item ?></option><?php endforeach; ?></select></label>
<button>Search / Filter</button><a href="manage_students.php">Clear</a></form>
<p><a href="export_students.php">Export CSV</a></p>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Course</th><th>Year</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php if ($students->num_rows === 0): ?><tr><td colspan="8">No students found.</td></tr><?php endif; ?>
<?php while($s=$students->fetch_assoc()): ?><tr><td><?= (int)$s['student_id'] ?></td><td><?= e($s['name']) ?></td><td><?= e($s['username']) ?></td><td><?= e($s['email']) ?></td><td><?= e($s['course']) ?></td><td><?= (int)$s['year'] ?></td><td><?= e($s['status']) ?></td><td><a href="edit_student.php?id=<?= (int)$s['student_id'] ?>">Edit</a>
<?php if ($s['status']==='Active'): ?><form method="post" action="delete_student.php" class="inline" onsubmit="return confirm('Are you sure you want to make this student Inactive?');"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="id" value="<?= (int)$s['student_id'] ?>"><button class="danger">Delete</button></form><?php endif; ?></td></tr><?php endwhile; ?>
</tbody></table></div></section></main></body></html>
