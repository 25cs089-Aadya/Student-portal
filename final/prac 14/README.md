# Practical 14 – StudentHub Admin Dashboard, RBAC & Audit Logs

Built for the existing Practical 8–13 database and Practical 10 login. PHP MySQLi + Bootstrap 5.

## Setup
1. Copy this folder to `C:\xampp\htdocs\prac 14\` (files must be directly inside that folder).
2. Start Apache/MySQL in XAMPP. Back up `studenthub_db` first via phpMyAdmin Export.
3. In phpMyAdmin select `studenthub_db` > SQL; run **schema.sql**. It only creates `audit_logs`, retaining prior data.
4. Log in through `http://localhost/prac%2010/login.php` as Admin, using the same browser.
5. Open `http://localhost/prac%2014/admin_dashboard.php`.
6. Visit `studenthub_db` > `audit_logs` > Browse in phpMyAdmin. Reload dashboard a couple of times to see log records.

## Student role test
Log out, log in as Student in Practical 10 and open `http://localhost/prac%2014/student_dashboard.php` to capture student menu. Then try `http://localhost/prac%2014/admin_dashboard.php` for a real HTTP 403 page. Log in again as Admin afterwards.

## Files
- `db.php` – MySQLi connection to `studenthub_db`
- `auth.php` – Practical 10 session check and inactivity timeout
- `dashboard.php` – role-based redirect
- `admin_dashboard.php` – live stats, latest registrations, Bootstrap admin menu, 403 handling
- `student_dashboard.php` – student-only menu
- `audit.php` – prepared INSERT into audit_logs
- `schema.sql` – new audit_logs table only
- `style.css` – StudentHub styling

## Screenshots
1. Admin dashboard with counts (users/students/events/registrations) and recent registrations
2. Admin-only menu links
3. Student dashboard with student-only menu
4. Student opens admin_dashboard.php: 403 Forbidden
5. phpMyAdmin audit_logs table (Browse) containing ADMIN_DASHBOARD_VIEW entries
6. (Optional) phpMyAdmin audit_logs Structure

## Notes
- The Practical 14 folder does NOT replace Practical 10's admin_dashboard.php. Use the new `/prac%2014/admin_dashboard.php` URL.
- `Registered Users` counts records with a password_hash. `Students` counts rows with Student role, including old Practical 8 seed students. If all students should mean every row, change the corresponding SQL to COUNT(*) FROM students.
- Latest registrations are **event registrations** from the Practical 8 `registrations` table, not newly created login accounts.
- Audit logging currently records real Admin Dashboard views. To log an EVENT_CREATED, EVENT_UPDATED, EVENT_DELETED or STUDENT_UPDATED action in earlier practicals, put an appropriate call to `writeAuditLog()` after the successful database operation in those files and include audit.php with the matching database connection. Do not claim those actions are logged until implemented and tested.
- Bootstrap CSS requires internet; the PHP/database features work locally regardless.
- Do not commit real database exports with student personal data or password hashes to public GitHub.
