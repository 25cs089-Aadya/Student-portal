# StudentHub - Final connected project

This distribution retains the original `index.html`, CSS, JS, and static course pages. Practical 5–14 folders are bundled for continuity. Practical 4 source was not in the uploaded RAR or previously available practical ZIPs; add your original Practical 4 folder separately.

## Run locally
1. Copy the **Student-portal** folder to `C:\xampp\htdocs\Student-portal`.
2. Start Apache and MySQL in XAMPP. Visit `http://localhost/Student-portal/index.html` (do not use VS Code Live Server to test PHP).
3. In phpMyAdmin ensure the **existing** `studenthub_db` has students, events, registrations and (for later practicals) username, password_hash, role, status, poster and audit_logs as appropriate. Back up the database first; do not re-run migrations blindly.
4. Use `Practical 9` registration to create a test account if you have none. The Practical 8 sample rows have no password hashes. Use `prac 10/login.php` to sign in. The account must have role `Admin` to open management modules.
5. From the home page, use Login / Dashboard / Admin Event Management links.

## Connections
- Home Login => `prac 10/login.php`; Register => `prac 9/register.html`.
- Dashboard => `prac 14/dashboard.php` (redirects to Admin or Student dashboard based on the login session).
- Browse Events stays a static informational mock-up; its admin link opens Practical 12 live management.
- Practical 11 manages students, Practical 12 manages events/posters, Practical 13 uses Fetch and `api.php` to manipulate the same events, Practical 14 shows live statistics and records dashboard visits in `audit_logs`.
- Cross-practical links now work when hosted in the `Student-portal` subfolder, not only when hosted at web-server root.

## Safety and limitations
- Existing Practical 4 is not present and must be copied separately if required.
- Static timetable, attendance, results, fees, and campus event cards remain demonstration data, not connected to MySQL. Do not present them as live user data.
- Registration, login and CRUD actions need a running PHP/MySQL server and a correctly migrated database.
- Admin CRUD is protected by role checks. Practical 13's `index.html` is a static shell, but its JSON API requires an Admin session.
- Never upload real exported user data, database passwords, or registration CSV files to a public repository.
- This package has been assembled and statically checked; full browser/database testing on your Windows XAMPP remains necessary.
