# Practical 5 — Student Registration Form

Open `register.html` in a browser (or with VS Code Live Server). This is a frontend validation demo; it does not save student data.

## Features
- Semantic HTML5 inputs and `required`, `pattern`, `autocomplete`, `inputmode` attributes
- JavaScript + regex checks for name, email, Indian mobile number and strong password
- Password confirmation, course/year/gender selection and terms checkbox validation
- Live password strength indicator, inline errors, keyboard access, and ARIA announcements

## Screenshots to submit
1. Complete registration form — open the page.
2. Valid input and success — complete all fields and click Register.
3. Invalid name/email/mobile — enter `John123`, `invalid@`, and `123`.
4. Password validation — enter `abc` and a nonmatching confirmation.
5. Password strength — compare `abc` against `Strong@123`.
6. Terms not accepted — valid form except unchecked Terms, then Register.
