-- Practical 12 update for the existing studenthub_db from Practical 8.
-- BACK UP studenthub_db FIRST. Run these statements ONLY ONCE.
-- Keep your existing events table and its registration links.
USE studenthub_db;
ALTER TABLE events
    MODIFY COLUMN event_date DATETIME NOT NULL,
    MODIFY COLUMN description TEXT NULL,
    ADD COLUMN status ENUM('Upcoming','Ongoing','Completed','Cancelled') NOT NULL DEFAULT 'Upcoming',
    ADD COLUMN poster VARCHAR(255) NULL;
-- Do not import a fresh CREATE TABLE / sample event INSERT script here.
