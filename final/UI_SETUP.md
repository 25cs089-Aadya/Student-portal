# StudentHub — Unified UI

The original homepage is unchanged. Practical pages have a shared visual theme (Inter, navy/emerald palette, rounded cards, navigation). No database or authentication logic was modified.

## Setup
1. Back up your existing `C:\xampp\htdocs\final` folder.
2. Extract this ZIP. Copy the CONTENTS of the `Student-portal` folder to `C:\xampp\htdocs\final`, replacing the files you choose (keep a backup).
3. Start Apache/MySQL and open `http://localhost/final/index.html`.
4. Login: `http://localhost/final/prac%2010/login.php`.
5. Admin: `http://localhost/final/prac%2014/admin_dashboard.php`.

The shared CSS is at `css/studenthub-unified.css`. Existing practical logic and the `studenthub_db` database stay untouched. Test session redirects and CRUD in your local XAMPP; stylesheet/syntax verification is not the same as live runtime testing.
