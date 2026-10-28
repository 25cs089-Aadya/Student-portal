-- Practical 9: Run ONCE in phpMyAdmin after selecting studenthub_db.
-- Keeps existing Practical 8 students, events and registrations.
USE studenthub_db;
ALTER TABLE students
  ADD COLUMN username VARCHAR(50) NULL UNIQUE,
  ADD COLUMN password_hash VARCHAR(255) NULL;
-- Existing Practical 8 rows remain NULL for these new fields.
-- New registrations supply both values.
