# StudentHub Practical 13 — PHP JSON API with Fetch CRUD

Simple second-year BTech project. Uses the same `studenthub_db.events` as Practical 12 and the Admin session from Practical 10 (key `student_id`). No new login page or separate database.

## Files
- `db.php` MySQLi connection
- `api.php` GET/POST/PUT/DELETE API (JSON responses)
- `index.html` event form and list
- `script.js` Fetch requests without reloading
- `schema.sql` safe table definition; not needed if Practical 12 already worked

## Run with XAMPP
1. Put this folder at `C:\xampp\htdocs\prac 13` (no additional folder layer).
2. Start Apache and MySQL.
3. **Do not reimport your old Practical 8 schema** or delete records. If you completed Practical 12, skip `schema.sql` because `events` already exists.
4. Log in as Admin using `http://localhost/prac%2010/login.php`.
5. In the same browser open `http://localhost/prac%2013/index.html`.
6. Add an event, edit it, then delete a NEW test event. Existing events that have registrations are protected from deletion.

## Screenshots
1. Event list visible.
2. Add event success message and new event in list.
3. Edit event and updated details.
4. Delete confirmation and success result (use a new test event with no registrations).
5. Loading message (DevTools Network > Slow 3G, then Refresh List).
6. Error state (log out through Practical 10; reload Practical 13 to show login-required error; log back in afterward).
7. Network tab: GET `api.php` -> 200, Response shows JSON.
8. Network tab: POST -> 201 with JSON; PUT -> 200; DELETE -> 200. Select each request, see Headers and Response tabs.

**Network steps:** F12 -> Network -> Fetch/XHR -> enable Preserve log -> use the page -> click `api.php` -> Headers/Response.

## Notes
- Practical 13 intentionally does not upload posters: that feature belongs to Practical 12.
- For tests of DELETE, use your own newly created event, not old events with registrations.
- Admin authorization, CSRF token and prepared statements are used for database changes.
- Do not upload real database exports, passwords, or user data to GitHub.
