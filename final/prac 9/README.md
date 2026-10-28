# StudentHub - Practical 9
**Secure registration with PHP MySQLi, duplicate checking, and hashed passwords**

This practical continues the StudentHub project from Practical 8. It uses the existing `studenthub_db` and `students` table.

## Requirements
- XAMPP Apache and MySQL running
- Practical 8 database (`studenthub_db`) already imported

## Setup
1. Back up `studenthub_db` from phpMyAdmin (Export) before changing its schema.
2. Open `http://localhost/phpmyadmin`, click database **studenthub_db** and select **SQL**.
3. Paste the contents of `update.sql` and click **Go**. Run this SQL **only once**. It adds `username` and `password_hash` to the existing students table without removing the old 3 rows or related events/registrations. Existing rows have NULL in the new fields.
4. Copy this project folder to `C:\xampp\htdocs\prac 9\` (or any folder name you prefer).
5. In Chrome go to `http://localhost/prac%209/register.html`.
6. Fill the form with fictional test details and submit.
7. In phpMyAdmin click **studenthub_db > students > Browse** to see the new record. The `password_hash` field contains a hash, not the original password.

## Files
- `register.html`: HTML form (POST method)
- `script.js`: basic frontend validation and password strength
- `process_register.php`: PHP server validation, duplicate checks and MySQL INSERT
- `db.php`: MySQLi database connection
- `update.sql`: adds two columns to existing Practical 8 students table
- `style.css`: matching StudentHub colors

## Screenshots to capture
1. Registration form
2. Empty-field validation (submit empty form)
3. Invalid email (with other fields valid)
4. Password mismatch (with other fields valid)
5. Successful registration
6. Duplicate email / username error (submit the same details again)
7. phpMyAdmin showing inserted user row and `password_hash` column

**Testing tip:** The frontend normally catches invalid fields before POST. To show PHP server errors, you can use a browser test with JavaScript disabled or remove `novalidate` only after testing if needed. Do not change your final submitted version unnecessarily.

**Do not upload** real user database exports or passwords to GitHub. `update.sql` contains structure changes only.
