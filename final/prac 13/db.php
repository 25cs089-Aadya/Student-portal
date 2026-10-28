<?php
// Use the same database as Practicals 8-12.
$host = "localhost";
$user = "root";
$password = ""; // Default local XAMPP setting only
$database = "studenthub_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    header("Content-Type: application/json; charset=UTF-8");
    exit(json_encode(["success" => false, "message" => "Database connection failed."]));
}
