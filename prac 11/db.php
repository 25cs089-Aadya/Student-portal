<?php
// Same database used in Practical 8, 9 and 10.
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');
if ($conn->connect_error) {
    exit('Database connection failed. Please check XAMPP MySQL.');
}
$conn->set_charset('utf8mb4');
