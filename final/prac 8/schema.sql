-- Practical 8: StudentHub Database
-- Import this file in phpMyAdmin (SQL tab or Import).
CREATE DATABASE IF NOT EXISTS studenthub_db;
USE studenthub_db;

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(10) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    gender VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Registered',
    FOREIGN KEY (student_id) REFERENCES students(student_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Sample test data. Import into a fresh database to avoid duplicates.
INSERT INTO students (name, email, mobile, course, year, gender) VALUES
('Aarav Patel', 'aarav.test@example.com', '9876500001', 'CSE', 2, 'Male'),
('Diya Shah', 'diya.test@example.com', '9876500002', 'IT', 2, 'Female'),
('Riya Mehta', 'riya.test@example.com', '9876500003', 'ECE', 1, 'Female');

INSERT INTO events (event_name, event_date, venue, description) VALUES
('Web Development Workshop', '2026-10-10', 'Computer Lab 1', 'Introduction to web development'),
('Coding Competition', '2026-10-15', 'Seminar Hall', 'Programming competition'),
('AI Seminar', '2026-10-20', 'Auditorium', 'Introduction to AI');

INSERT INTO registrations (student_id, event_id, registration_date, status) VALUES
(1, 1, '2026-10-04', 'Registered'),
(2, 2, '2026-10-04', 'Registered'),
(3, 3, '2026-10-04', 'Registered');
