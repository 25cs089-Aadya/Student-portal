# StudentHub - Practical 7

**ITUE203: Web Development Frameworks**

This continues the StudentHub registration form from Practical 5. In Practical 5 we used JavaScript to check the form. In Practical 7 PHP checks the same details on the server and saves valid records to a CSV file.

## Files
- `register.html` - Student registration form (`method="POST"`)
- `process_register.php` - PHP validation, sanitization, success/errors, CSV saving
- `style.css` - Simple StudentHub design
- `data/registrations.csv` - Created automatically after successful registration (ignored by Git)

## How to run using XAMPP
1. Install/start **XAMPP** and start **Apache**.
2. Copy the `StudentHub_Practical7` folder into XAMPP's `htdocs` folder.
3. Open `http://localhost/StudentHub_Practical7/register.html` in a browser.
4. Complete all fields and click Register. A success page will appear.
5. Open `data/registrations.csv` to see the stored data.
6. To test server errors, submit invalid values. For empty-field server validation, you can temporarily remove the `required` attribute in browser DevTools, or submit via a tool that sends POST requests.

**Important:** VS Code Live Server does not run PHP. Do not just double-click the HTML file. Use XAMPP or another PHP-enabled server.

## Required screenshots
1. Complete registration form UI
2. Successful submission page
3. Validation error page (for example mismatched passwords)
4. CSV file opened in VS Code showing the saved record

Use fake details for screenshots. Never upload real student personal data or passwords. Passwords are not stored in CSV. The CSV file is generated on your own computer and is ignored by Git on purpose.

## GitHub
Copy this folder into your cloned `Student-portal` repository as `Practical7/`. Commit the source code (`register.html`, `process_register.php`, `style.css`, `README.md`, and `data/.gitignore`), not your personal CSV records.
