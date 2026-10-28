-- Practical 13: Works with the SAME studenthub_db from Practicals 8-12.
-- The CREATE TABLE statement below only creates a table if one is missing.
-- Do NOT drop the existing events table or import fake student data.
CREATE DATABASE IF NOT EXISTS studenthub_db;
USE studenthub_db;

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATETIME NOT NULL,
    venue VARCHAR(200) NOT NULL,
    description TEXT,
    status ENUM('Upcoming', 'Ongoing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Upcoming',
    poster VARCHAR(255) DEFAULT NULL
);
-- If you already completed Practical 12, there is no need to run this SQL.
