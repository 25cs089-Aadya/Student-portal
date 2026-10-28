<?php
require_once "auth.php";
require_once "db.php";

function go($msg) { header("Location: events.php?msg=" . urlencode($msg)); exit; }
if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: events.php"); exit; }
if (!hash_equals($_SESSION["event_token"], $_POST["token"] ?? "")) go("token");

$id = trim($_POST["id"] ?? "");
$id = $id === "" ? 0 : filter_var($id, FILTER_VALIDATE_INT);
$title = trim($_POST["title"] ?? "");
$date = trim($_POST["date"] ?? "");
$venue = trim($_POST["venue"] ?? "");
$description = trim($_POST["description"] ?? "");
$status = $_POST["status"] ?? "";
$dateObj = DateTime::createFromFormat("!Y-m-d\TH:i", $date);
if ($id === false || $id < 0 || strlen($title) < 3 || strlen($title) > 100 ||
    $venue === "" || strlen($venue) > 100 || $description === "" || strlen($description) > 5000 ||
    !$dateObj || $dateObj->format('Y-m-d\TH:i') !== $date ||
    !in_array($status, ["Upcoming", "Ongoing", "Completed", "Cancelled"], true)) go("invalid");
$date = $dateObj->format('Y-m-d H:i:s');

$oldPoster = "";
if ($id > 0) {
    $stmt = $conn->prepare("SELECT poster FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row) go("missing");
    $oldPoster = $row["poster"] ?? "";
}
$poster = $oldPoster;
$newPath = "";

if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES["poster"];
    if (in_array($file["error"], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) go("size");
    if ($file["error"] !== UPLOAD_ERR_OK || !is_uploaded_file($file["tmp_name"])) go("upload");
    if ($file["size"] <= 0 || $file["size"] > 2 * 1024 * 1024) go("size");
    $types = ["image/jpeg" => "jpg", "image/png" => "png", "image/webp" => "webp"];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);
    $info = @getimagesize($file["tmp_name"]);
    if (!isset($types[$mime]) || $info === false || ($info['mime'] ?? '') !== $mime) go("type");
    $dir = __DIR__ . '/uploads/events';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) go("upload");
    $filename = bin2hex(random_bytes(16)) . '.' . $types[$mime];
    $newPath = $dir . '/' . $filename;
    if (!move_uploaded_file($file["tmp_name"], $newPath)) go("upload");
    $poster = 'uploads/events/' . $filename;
}

try {
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE events SET event_name=?, event_date=?, venue=?, description=?, status=?, poster=? WHERE event_id=?");
        $stmt->bind_param("ssssssi", $title, $date, $venue, $description, $status, $poster, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, venue, description, status, poster) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $title, $date, $venue, $description, $status, $poster);
    }
    $stmt->execute();
    $stmt->close();
} catch (mysqli_sql_exception $ex) {
    error_log($ex->getMessage());
    if ($newPath !== '' && is_file($newPath)) unlink($newPath);
    go("failed");
}
if ($newPath !== '' && $oldPoster !== '' && preg_match('~^uploads/events/[a-f0-9]{32}\.(jpg|png|webp)$~', $oldPoster)) {
    $oldPath = __DIR__ . '/' . $oldPoster;
    if (is_file($oldPath)) unlink($oldPath);
}
go($id > 0 ? "updated" : "created");
