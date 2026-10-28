# Practical 10 — StudentHub Secure Login

A simple second-year BTech practical that **continues the same `studenthub_db` from Practical 8 and 9**. Uses PHP MySQLi, `password_verify()`, sessions, role checks, and a 15-minute inactivity timeout.

## Files
- db.php: MySQLi connection to `studenthub_db`
- login.php: email/password form
- authenticate.php: prepared statement, password verification, session regeneration
- dashboard.php: checks session and redirects by role
- admin_dashboard.php: Admin-only page
- student_dashboard.php: Student-only page
- logout.php: destroys login session
- timeout.php: handles inactive sessions
- session_test.php: proves ID regeneration without displaying the secret ID
- style.css: StudentHub theme
- user_registration.sql: adds a role column to the existing database and documents export

## Setup (XAMPP)
1. Start **Apache** and **MySQL** in XAMPP.
2. Back up your existing `studenthub_db` via phpMyAdmin Export first.
3. If Practical 9 is not completed yet, finish it first. This login uses `students.email` and `students.password_hash` from Practical 9. Old Practical 8 rows without a password hash cannot log in.
4. In phpMyAdmin, select `studenthub_db`, open SQL, and run the **ALTER TABLE** in `user_registration.sql` **once**. Don't recreate or reimport the database.
5. Copy this folder to `C:\xampp\htdocs\prac 10`.
6. Visit `http://localhost/prac%2010/login.php` (not VS Code Live Server).
7. Log in using an account registered in Practical 9.

## Admin test account
1. Use Practical 9 registration to create a separate test account with a known test password.
2. In phpMyAdmin SQL, execute (changing the email):
   `UPDATE students SET role='Admin' WHERE email='admin.test@example.com';`
3. Normal registered accounts keep `role='Student'`. Do not let public users choose Admin during registration.

## Required screenshots (9)
1. Successful Student login (`student_dashboard.php`)
2. Successful Admin login (`admin_dashboard.php`)
3. Invalid email/password (`login.php?msg=invalid` after a wrong password)
4. Logged-in Student opens `admin_dashboard.php` → Unauthorized Access
5. Logged-in Admin opens `student_dashboard.php` → Unauthorized Access
6. Logout → `login.php?msg=logout`
7. After logout try opening `dashboard.php` → login required
8. Session timeout → `login.php?msg=timeout` (for faster testing, change `900` to `15` in dashboard.php AND both role dashboard files, log in, stay inactive 16+ seconds and reload; **restore 900** afterwards)
9. Session regeneration → log in, then visit `session_test.php` showing PASS

## Export for submission
Your faculty requests `user_registration.sql` exported from MySQL with users and roles. Export your test database from phpMyAdmin after adding demo accounts. **Do not put this actual user dump on a public GitHub repo.** You can submit it privately to your faculty, while keeping the included migration `user_registration.sql` in GitHub. Don't export real users or their password hashes publicly.

## Security notes
- Passwords are checked against the hashes created by Practical 9; never store or compare plaintext passwords.
- Only users assigned Admin in the database have Admin access.
- Session IDs are secret and are not displayed by the test page.
- Remember-me tokens are optional in the reference problem statement and are not needed for your checklist.
