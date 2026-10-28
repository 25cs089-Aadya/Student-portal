-- Practical 11: Select studenthub_db in phpMyAdmin, then run this ONCE.
-- Back up your database first. Practical 8-10 records are preserved.
USE studenthub_db;
ALTER TABLE students ADD COLUMN status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active';
