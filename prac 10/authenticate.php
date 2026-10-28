<?php
session_start();
require_once "db.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php"); exit;
}
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
if ($email === "" || $password === "") {
    header("Location: login.php?msg=empty"); exit;
}

// MySQLi prepared statement prevents SQL injection.
$stmt = $conn->prepare("SELECT student_id, name, email, password_hash, role FROM students WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$student || !$student["password_hash"] || !password_verify($password, $student["password_hash"])) {
    header("Location: login.php?msg=invalid"); exit;
}
if (!in_array($student["role"], ["Admin", "Student"], true)) {
    header("Location: login.php?msg=invalid"); exit;
}

// Change the session ID after login to prevent session fixation.
$before = session_id();
session_regenerate_id(true);
$_SESSION["id_changed"] = ($before !== session_id());
$_SESSION["student_id"] = $student["student_id"];
$_SESSION["name"] = $student["name"];
$_SESSION["role"] = $student["role"];
$_SESSION["last_activity"] = time();
header("Location: dashboard.php");
exit;
?>
