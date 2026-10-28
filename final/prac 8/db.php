<?php
// StudentHub - Practical 8 database connection
$host = "localhost";
$dbname = "studenthub_db";
$username = "root";
$password = ""; // Default local XAMPP setting; change if needed.

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Don't reveal your database password or server details on the webpage.
    error_log($e->getMessage());
    die("Database connection failed. Check MySQL and your database settings.");
}
?>
