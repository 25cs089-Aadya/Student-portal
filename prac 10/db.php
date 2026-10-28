<?php
// Same database used in Practical 8 and 9.
$host = "localhost";
$user = "root";
$password = "";
$database = "studenthub_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    exit("Database connection failed. Check XAMPP MySQL.");
}
?>
