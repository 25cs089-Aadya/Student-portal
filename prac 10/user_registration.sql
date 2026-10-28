-- Practical 10: Database update for the existing Practical 9 studenthub_db.
-- Back up the database before running. Run the ALTER TABLE just once.
-- Existing Practical 8/9 data remains unchanged.
USE studenthub_db;
ALTER TABLE students
  ADD COLUMN role ENUM('Admin', 'Student') NOT NULL DEFAULT 'Student';

-- New Practical 9 registrations automatically get the Student role.
-- To prepare an admin TEST account:
-- 1. Register a test account through Practical 9 first.
-- 2. Replace admin.test@example.com with that account's email.
-- 3. Run this separately in phpMyAdmin SQL (without the leading --):
-- UPDATE students SET role = 'Admin' WHERE email = 'admin.test@example.com';

-- To EXPORT actual registered users and their roles for the assignment:
-- phpMyAdmin > studenthub_db > Export > Quick > SQL.
-- Keep real student emails and password hashes out of public GitHub.
