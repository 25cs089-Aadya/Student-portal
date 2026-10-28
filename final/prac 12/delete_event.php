<?php
require_once "auth.php";
require_once "db.php";
function go($msg) { header("Location: events.php?msg=" . urlencode($msg)); exit; }
if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: events.php"); exit; }
if (!hash_equals($_SESSION["event_token"], $_POST["token"] ?? "")) go("token");
$id = filter_var($_POST["id"] ?? "", FILTER_VALIDATE_INT);
if (!$id || $id < 1) go("missing");
$stmt = $conn->prepare("SELECT poster FROM events WHERE event_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) go("missing");
// Avoid deleting registrations created in Practical 8.
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM registrations WHERE event_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$count = (int)$stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
if ($count > 0) go("linked");
try {
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
} catch (mysqli_sql_exception $ex) { error_log($ex->getMessage()); go("failed"); }
$poster = $row['poster'] ?? '';
if (preg_match('~^uploads/events/[a-f0-9]{32}\.(jpg|png|webp)$~', $poster)) {
    $path = __DIR__ . '/' . $poster;
    if (is_file($path)) unlink($path);
}
go("deleted");
