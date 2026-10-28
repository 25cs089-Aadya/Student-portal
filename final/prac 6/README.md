# StudentHub – Practical 6

**Web Development Frameworks (ITUE203)**

**Topic:** Rendering external JSON data using Fetch API, Search and Filter.

## Run

1. Open this `StudentHub_Practical6` folder in Visual Studio Code.
2. Install the **Live Server** extension if needed.
3. Right-click `notice.html` → **Open with Live Server**. Do **not** open via a `file://` address, because Fetch API may be blocked for local files.
4. The page fetches `data/events.json`, `data/students.json`, and `data/faqs.json`.

## Files

- `notice.html`: main events and dataset page
- `style.css`: responsive StudentHub design
- `js/app.js`: loading, UI state, filtering and event listeners
- `js/data.js`: reusable Fetch API helper
- `js/ui.js`: modular sorting, pagination and DOM rendering
- `data/events.json`: 15 event records (following the sample manual)
- `data/students.json`: 15 **sample, fictional** student profiles
- `data/faqs.json`: 15 example FAQs

## Screenshot checklist (7)

1. **JSON data displayed:** initial page with event cards and counts.
2. **Search result:** type `AI` or `Python` in search box; capture matching events.
3. **Filter result:** select `Competition`; capture the category-filtered cards.
4. **Sorting result:** choose `Name: A to Z` or `Date: oldest first` and show changed ordering.
5. **Pagination:** clear filters; take a screenshot with `1`, `2`, `3` buttons (5 cards per page).
6. **Loading:** click Reload data and screenshot the temporary loading state (the app leaves it visible for at least 700ms).
7. **Error:** temporarily rename `data/events.json` to `events_backup.json`, reload the page and capture the red error message; **rename back to `events.json` afterward**.

## Requirements covered

- 3 JSON files with 15 records each
- Fetch API to load external JSON
- Dynamic cards rendered from data
- Search (case-insensitive, name/description/category)
- Category filter
- Sorting by date or name
- Pagination, 5 records per page
- Loading and clear error states with manual retry
- JavaScript ES modules split into 3 files
- Accessible labels, keyboard controls, and text-only DOM rendering

## Adding to existing GitHub Student-portal repository

Safest: copy this entire folder into your **cloned** `Student-portal-GitHub` repository, keeping your existing pages and assets untouched. Then run:

```powershell
git add StudentHub_Practical6
git commit -m "Add Practical 6 Fetch API and JSON rendering"
git push origin main
```

Adjust the `git add` path if you renamed the folder to `Practical6`.
