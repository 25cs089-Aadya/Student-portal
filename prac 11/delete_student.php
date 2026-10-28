<?php
require_once 'auth.php'; require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: manage_students.php');exit; }
check_token();
$id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) {header('Location: manage_students.php?error=invalid');exit;}
$stmt=$conn->prepare("UPDATE students SET status='Inactive' WHERE student_id=? AND role='Student'");
$stmt->bind_param('i',$id);
try { $stmt->execute(); header('Location: manage_students.php?status=All&message=deleted'); }
catch(mysqli_sql_exception $ex) { header('Location: manage_students.php?error=failed'); }
exit;
