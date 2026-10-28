# StudentHub - Practical 8

**Topic:** MySQL Schema Design, PDO Connectivity, and Prepared Statements.

Continues the StudentHub project: Practical 5 and 7 used a student registration form; Practical 8 stores sample student, event, and event-registration data in related MySQL tables and reads student records through PHP.

## Files
- `schema.sql`: three tables (`students`, `events`, `registrations`) with primary and foreign keys, plus sample data.
- `db.php`: PDO database connection with try/catch handling.
- `test_connection.php`: prepared `SELECT` statement and HTML student table.
- `style.css`: basic StudentHub theme.

## Run using XAMPP
1. Start **Apache** and **MySQL** in XAMPP.
2. Copy the `StudentHub_Practical8` folder into `C:\xampp\htdocs\` (or your XAMPP installation's `htdocs`).
3. Open `http://localhost/phpmyadmin` and choose **Import**. Select `schema.sql` and click **Import/Go**. Use a fresh `studenthub_db` database: running the test inserts again may cause duplicate email errors.
4. Open `http://localhost/StudentHub_Practical8/test_connection.php`.
5. Check that the green connection message and student table appear.

`db.php` assumes default *local* XAMPP credentials (`root` with an empty password). Change these settings if your local MySQL configuration is different. For deployed projects, use a dedicated database user and a password.

## Screenshots
1. **Browser:** `test_connection.php` showing successful connection and Students Table Output (required screenshot).
2. **phpMyAdmin:** `studenthub_db` with `students`, `events`, and `registrations` tables (optional evidence).

## GitHub
Upload the code files (`schema.sql`, `db.php`, `test_connection.php`, `style.css`, `README.md`) in a `Practical8` folder of your Student-portal repository. Do not upload real student information or private database credentials. The sample data is fictional.

**Viva idea:** Students and events are stored separately, and registrations links them using `student_id` and `event_id` foreign keys. A PDO prepared statement keeps query values separate from the SQL statement.
