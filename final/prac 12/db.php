<?php
// Same database used in Practical 8 to Practical 11.
$host = "localhost";
$user = "root";
$password = "";  // Default local XAMPP configuration
$database = "studenthub_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    error_log($conn->connect_error);
    exit("Database connection failed. Check XAMPP MySQL.");
}
$conn->set_charset("utf8mb4");
