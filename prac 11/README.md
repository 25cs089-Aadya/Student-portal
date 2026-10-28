# StudentHub Practical 11 – corrected version

This version is designed for the **same setup as Practical 10**: XAMPP, `studenthub_db`, MySQLi, and the `students` table.

## What was fixed

The previous Practical 11 looked for `$_SESSION['user_id']`. But the actual Practical 10 code saves `$_SESSION['student_id']` after login. The new `auth.php` uses the correct key and matches Practical 10's 15-minute timeout and its `?msg=login`/`?msg=timeout` messages. **No Practical 10 files need replacing.**

## Install

1. Back up your existing `C:\xampp\htdocs\prac 11` folder (rename it `prac 11 old` if necessary).
2. Extract this ZIP and copy its **contents** straight into `C:\xampp\htdocs\prac 11` (do not leave an extra nested folder).
3. Keep the original Practical 10 at `C:\xampp\htdocs\prac 10`. Use the same Chrome browser and the same `localhost` hostname for both practicals.
4. Start Apache and MySQL in XAMPP.
5. In phpMyAdmin, check that `studenthub_db.students` has a `status` column. Only if missing: back up the database, then run the command in `update.sql` once.
6. Log in as an Admin at `http://localhost/prac%2010/login.php`.
7. Open `http://localhost/prac%2011/manage_students.php` in another tab **of the same browser**.

**Note:** The Practical 10 admin dashboard does not yet have a Manage Students button. That's okay: open the Practical 11 URL directly. You can optionally add `<a href="../prac%2011/manage_students.php">Manage Students</a>` to Practical 10's `admin_dashboard.php` later.

## Features / screenshots

1. Student records table.
2. Add a test student → success message.
3. Edit the student → updated message.
4. Search by name or email.
5. Filter by course.
6. Click Delete → screenshot of confirmation popup.
7. Confirm → choose All statuses to see Inactive.
8. Duplicate email/username → error message.
9. Export CSV → open the downloaded file in Excel.

Student data is stored in `studenthub_db.students`, not a separate `users` table. New students get role `Student` and a password hash. All database changes use MySQLi prepared statements. Exported CSV excludes password hashes.

Do not upload live student records or database exports to a public GitHub repository.

## Files

`auth.php`, `db.php`, `manage_students.php`, `edit_student.php`, `delete_student.php`, `export_students.php`, `update.sql`, `style.css`, `README.md`.
