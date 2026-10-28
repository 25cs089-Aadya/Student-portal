
# StudentHub Portal

**Practical 15 – Final Integration, Testing, Documentation and Deployment**

**Enrollment No.:** 25CS089  
**Course:** ITUE203 – Web Development Frameworks  
**Semester:** 3

## Project Overview

StudentHub is a student management web application developed using HTML, CSS, JavaScript, PHP and MySQL.

It integrates the practical assignments into one project with student registration, login, role-based dashboards, student management, event management, database operations and Fetch API functionality.

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL / MariaDB
- XAMPP (Apache and MySQL)
- phpMyAdmin
- Git and GitHub

## Project Features

- Responsive StudentHub homepage
- Student registration with password hashing
- Login and logout using PHP sessions
- Admin and Student role-based access
- Student record management (Add, View, Edit, Delete)
- Search and filter students
- CSV export
- Event creation and management
- Event poster uploads
- Student event registration
- Fetch API with GET, POST, PUT and DELETE
- Dynamic dashboard statistics
- Latest registrations
- Audit logs
- Input validation and access restrictions

## Installation and Setup

### Step 1: Install XAMPP

Install XAMPP and start:

- Apache
- MySQL

### Step 2: Download the Project

Clone the repository:

```bash
git clone https://github.com/25cs089-Aadya/Student-portal.git
```

Place the project inside:

```text
C:\xampp\htdocs\
```

Rename the project folder to `Student-portal` if necessary.

### Step 3: Database Setup

1. Open `http://localhost/phpmyadmin`
2. Create a database named `studenthub_db`.
3. Import the provided SQL database file.
4. Check the PHP database connection settings.
5. Confirm that the required tables are available.

Main database tables include:

- `students`
- `events`
- `registrations`
- `audit_logs`

**Note:** A full database dump containing personal records or password hashes should be submitted privately rather than committed to a public repository.

### Step 4: Run the Project

Open the integrated homepage:

```text
http://localhost/Student-portal/final/index.html
```

Login page:

```text
http://localhost/Student-portal/final/prac%2010/login.php
```

Practical 14 Admin Dashboard:

```text
http://localhost/Student-portal/final/prac%2014/admin_dashboard.php
```

If you copied the `final` folder directly into `htdocs`, use `http://localhost/final/` instead.

## Testing

The following features should be tested before submission:

| Test | Expected Result |
|------|-----------------|
| Homepage | Loads correctly |
| Registration | Valid user is registered |
| Duplicate email | Registration is rejected |
| Login | Valid credentials allow access |
| Invalid login | Error message is displayed |
| Role-based access | Users access only permitted pages |
| Student CRUD | Records can be managed by Admin |
| Events | Admin can manage events |
| Event registration | Student can register for events |
| Fetch API | Requests return expected responses |
| Dashboard | Dynamic statistics are displayed |
| Audit logs | Supported actions are recorded |
| Logout | User session is ended |
| Responsive design | Pages adapt to mobile screens |

Actual results and observations are documented in `TEST_REPORT.md`.

## Security Features

- Hashed passwords using PHP `password_hash()`
- Password verification during login
- PHP session-based authentication
- Role-based access control
- Server-side input validation
- Restricted administrator pages
- Database query protection where prepared statements are used
- File upload restrictions
- Audit logging

## Screenshots

The `screenshots/` folder contains evidence of the application's functionality, including:

- Homepage
- Registration
- Student Dashboard
- Admin Dashboard
- Student Management
- Event Management
- Unauthorized access
- Fetch API
- Dynamic statistics
- Audit logs

## GitHub Repository

https://github.com/25cs089-Aadya/Student-portal

## Conclusion

The StudentHub Portal combines the frontend and backend practical assignments into a single web application using HTML, CSS, JavaScript, PHP and MySQL.

The project demonstrates database connectivity, authentication, role-based authorization, CRUD operations, event management, AJAX functionality, testing, security and local deployment using XAMPP.

The final submission includes the source code, database dump, README documentation, testing report and application screenshots.
