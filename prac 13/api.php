<?php
// Practical 13: A small JSON API for StudentHub events.
// GET = list, POST = add, PUT = edit, DELETE = delete.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

function sendJson($code, $data) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Practical 10 uses student_id, not user_id.
if (!isset($_SESSION["student_id"], $_SESSION["role"])) {
    sendJson(401, ["success" => false, "message" => "Please log in as Admin through Practical 10."]);
}
if (!isset($_SESSION["last_activity"]) || time() - $_SESSION["last_activity"] > 900) {
    $_SESSION = [];
    session_destroy();
    sendJson(401, ["success" => false, "message" => "Session expired. Please log in again."]);
}
if ($_SESSION["role"] !== "Admin") {
    sendJson(403, ["success" => false, "message" => "Unauthorized Access: Admin only."]);
}
$_SESSION["last_activity"] = time();

if (empty($_SESSION["api_token"])) {
    $_SESSION["api_token"] = bin2hex(random_bytes(16));
}

require_once "db.php";

function bodyData() {
    $data = json_decode(file_get_contents("php://input"), true);
    return is_array($data) ? $data : [];
}
function validEvent($data) {
    $name = trim((string)($data["event_name"] ?? ""));
    $date = trim((string)($data["event_date"] ?? ""));
    $venue = trim((string)($data["venue"] ?? ""));
    $description = trim((string)($data["description"] ?? ""));
    $status = (string)($data["status"] ?? "Upcoming");
    $dateObject = DateTime::createFromFormat('!Y-m-d\TH:i', $date);
    if ($name === "" || strlen($name) > 100 || $venue === "" || strlen($venue) > 200 ||
        $description === "" || strlen($description) > 5000 ||
        !in_array($status, ["Upcoming", "Ongoing", "Completed", "Cancelled"], true) ||
        !$dateObject || $dateObject->format('Y-m-d\TH:i') !== $date) {
        sendJson(422, ["success" => false, "message" => "Please enter valid event details."]);
    }
    return [$name, $dateObject->format("Y-m-d H:i:s"), $venue, $description, $status];
}
function eventId($data) {
    $id = filter_var($data["event_id"] ?? null, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        sendJson(422, ["success" => false, "message" => "Invalid event ID."]);
    }
    return $id;
}

$method = $_SERVER["REQUEST_METHOD"];
if (!in_array($method, ["GET", "POST", "PUT", "DELETE"], true)) {
    header("Allow: GET, POST, PUT, DELETE");
    sendJson(405, ["success" => false, "message" => "Method not allowed."]);
}
if ($method !== "GET") {
    $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
    if (!$token || !hash_equals($_SESSION["api_token"], $token)) {
        sendJson(403, ["success" => false, "message" => "Invalid request token. Refresh the page."]);
    }
}

try {
    if ($method === "GET") {
        $result = $conn->query("SELECT event_id, event_name, event_date, venue, description, status FROM events ORDER BY event_id DESC");
        sendJson(200, ["success" => true, "data" => $result->fetch_all(MYSQLI_ASSOC), "csrf_token" => $_SESSION["api_token"]]);
    }

    $data = bodyData();
    if ($method === "POST") {
        [$name, $date, $venue, $description, $status] = validEvent($data);
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, venue, description, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $date, $venue, $description, $status);
        $stmt->execute();
        sendJson(201, ["success" => true, "message" => "Event added successfully."]);
    }
    $id = eventId($data);
    if ($method === "PUT") {
        [$name, $date, $venue, $description, $status] = validEvent($data);
        $stmt = $conn->prepare("UPDATE events SET event_name=?, event_date=?, venue=?, description=?, status=? WHERE event_id=?");
        $stmt->bind_param("sssssi", $name, $date, $venue, $description, $status, $id);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $check = $conn->prepare("SELECT event_id FROM events WHERE event_id=?");
            $check->bind_param("i", $id);
            $check->execute();
            if ($check->get_result()->num_rows === 0) {
                sendJson(404, ["success" => false, "message" => "Event not found."]);
            }
        }
        sendJson(200, ["success" => true, "message" => "Event updated successfully."]);
    }
    // Avoid deleting Practical 8 registration history by accident.
    $check = $conn->prepare("SELECT event_id FROM events WHERE event_id=?");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        sendJson(404, ["success" => false, "message" => "Event not found."]);
    }
    $check = $conn->prepare("SELECT registration_id FROM registrations WHERE event_id=? LIMIT 1");
    $check->bind_param("i", $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        sendJson(409, ["success" => false, "message" => "This event has registrations. Delete a test event without registrations instead."]);
    }
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    sendJson(200, ["success" => true, "message" => "Event deleted successfully."]);
} catch (Throwable $e) {
    error_log("Practical 13 API error: " . $e->getMessage());
    sendJson(500, ["success" => false, "message" => "Database operation failed. Check XAMPP and the events table."]);
}
