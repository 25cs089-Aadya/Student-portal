<?php
require_once 'auth.php'; require_once 'db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_token();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? ''); $course = $_POST['course'] ?? '';
    $year = (int)($_POST['year'] ?? 0); $gender = $_POST['gender'] ?? '';
    if (!$id || strlen($name)<3 || !filter_var($email,FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10}$/',$mobile)
        || !in_array($course,['CSE','IT','CE','ECE','ME'],true) || $year<1 || $year>4
        || !in_array($gender,['Male','Female','Other'],true)) {
        header('Location: edit_student.php?id='.(int)$id.'&error=invalid');exit;
    }
    $stmt=$conn->prepare("UPDATE students SET name=?,email=?,mobile=?,course=?,year=?,gender=? WHERE student_id=? AND role='Student'");
    $stmt->bind_param('ssssisi',$name,$email,$mobile,$course,$year,$gender,$id);
    try { $stmt->execute(); header('Location: manage_students.php?message=updated'); }
    catch(mysqli_sql_exception $ex) { header('Location: edit_student.php?id='.$id.'&error='.($ex->getCode()==1062?'duplicate':'failed')); }
    exit;
}
if (!$id) {header('Location: manage_students.php?error=notfound');exit;}
$stmt=$conn->prepare("SELECT * FROM students WHERE student_id=? AND role='Student'");
$stmt->bind_param('i',$id);$stmt->execute();$student=$stmt->get_result()->fetch_assoc();
if (!$student) {header('Location: manage_students.php?error=notfound');exit;}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Student | StudentHub</title><link rel="stylesheet" href="style.css"><link rel="stylesheet" href="../css/studenthub-unified.css">
</head><body class="hub-unified"><header class="hub-topbar"><div class="hub-topbar-inner"><a class="hub-brand" href="../index.html">🎓 StudentHub</a><nav class="hub-links" aria-label="StudentHub navigation"><a href="../index.html">Home</a><a href="../pages/timetable.html">Time Table</a><a href="../pages/attendance.html">Attendance</a><a href="../pages/events.html">Events</a><a href="../prac%2014/dashboard.php">Dashboard</a><a class="hub-login" href="../prac%2010/login.php">Login</a></nav></div></header><header><h1>🎓 StudentHub</h1><p>Edit Student</p></header><main class="container narrow"><h2>Edit Student Details</h2>
<?php if(isset($_GET['error'])): ?><p class="error">Update failed: check details or duplicate email.</p><?php endif; ?>
<form method="post" class="form-grid"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="id" value="<?= $id ?>">
<label>Name<input name="name" required value="<?= e($student['name']) ?>"></label><label>Email<input name="email" type="email" required value="<?= e($student['email']) ?>"></label><label>Mobile<input name="mobile" required pattern="[0-9]{10}" value="<?= e($student['mobile']) ?>"></label>
<label>Course<select name="course"><?php foreach(['CSE','IT','CE','ECE','ME'] as $item): ?><option <?= $item===$student['course']?'selected':'' ?>><?= $item ?></option><?php endforeach; ?></select></label>
<label>Year<select name="year"><?php for($y=1;$y<=4;$y++): ?><option value="<?= $y ?>" <?= $y==(int)$student['year']?'selected':'' ?>><?= $y ?></option><?php endfor; ?></select></label>
<label>Gender<select name="gender"><?php foreach(['Male','Female','Other'] as $item): ?><option <?= $item===$student['gender']?'selected':'' ?>><?= $item ?></option><?php endforeach; ?></select></label>
<button>Save Changes</button><a href="manage_students.php">Cancel</a></form></main></body></html>
