# StudentHub Practical 12 — Event Management

A simple PHP + MySQLi practical connected to Practical 8–11. Uses `studenthub_db`, the existing `events` and `registrations` tables, and the **Practical 10 Admin login**.

## Setup in XAMPP

1. Copy this folder to `C:\xampp\htdocs\prac 12` (make sure `events.php` is directly inside).
2. Start Apache and MySQL in XAMPP.
3. In phpMyAdmin, **export a backup of `studenthub_db`**.
4. Select `studenthub_db` → SQL. If its `events` table does not already have `status` and `poster`, paste and execute `update.sql` **once**. This alters the existing table; do not overwrite your Practical 8 data.
5. Log in to `http://localhost/prac%2010/login.php` with your Admin account.
6. In the same browser, open `http://localhost/prac%2012/events.php`.

**Important:** Do not mix in the friend's SQL schema: it creates a different events table. This code expects the same event columns that Practical 8 created plus the two new columns.

## Files

- `events.php` — add/edit form and events with posters
- `save_event.php` — input validation, secure image upload, insert/update
- `delete_event.php` — delete a test event after confirmation
- `auth.php` — checks Practical 10 session (`student_id`, `role`, `last_activity`)
- `db.php` — MySQLi connection to `studenthub_db`
- `style.css` — simple matching StudentHub look
- `update.sql` — once-only migration for existing database
- `uploads/events/` — generated poster files at runtime

## Screenshots — take 10

1. Admin dashboard (Practical 10)
2. Successful event creation (green message)
3. Event list with uploaded posters
4. Successful event update
5. Successful event deletion (create and delete a fresh test event)
6. Valid poster upload (event card showing image)
7. Invalid file type (try a `.txt` file through file picker set to All Files)
8. Over-2-MB image validation (use an actual image over 2 MB)
9. Success and error messages (can reuse #2 and #7 or capture separately)
10. Unauthorized access (log in as Student, then try `events.php`)

## Important notes

- Images permitted: actual JPG, PNG and WebP, maximum 2 MB. Validation is on the server.
- Event deletion is blocked for events referenced by `registrations` (Practical 8) so your old registration data is not accidentally removed. Delete an event you created for this test.
- This project does **not** include a copy of login; it uses the working Practical 10 session in the same Chrome browser.
- Files uploaded into `uploads/events/` and real database backups should not be published to GitHub.
- If your PHP login uses different keys from `student_id` and `role`, verify the actual Practical 10 `authenticate.php` before changing this project.
- For a local XAMPP college practical, `db.php` uses the default `root` with blank password. Do not deploy these credentials to a public server.
