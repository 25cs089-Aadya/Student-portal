<?php
// Local XAMPP connection for StudentHub Practical 9
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'studenthub_db';

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die('Database connection failed. Check XAMPP MySQL and db.php.');
}
