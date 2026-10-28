# StudentHub Portal

A modern, responsive, and accessibility-ready student portal designed for academic planning, course schedules, finance management, and campus events.

---

## 🛠️ Technology Stack & Visual Architecture

* **Markup:** Semantic HTML5 structured for assistive screen-readers (including Skip-Links, focus outline control, and clear section dividers).
* **Styles:** Vanilla CSS3 styled with a custom dark glassmorphism system using custom color variables (HSL system), grid layouts, flex containers, and fluid responsiveness.
* **Scripts:** ES6 JS managing mobile navigation drawers and accessibility attributes dynamically.

---

## 📂 Project Structure

The project has been restructured to isolate style assets, scripts, inner sub-pages, and documentation artifacts:

```
Student-portal/
│
├── index.html               # Main entry portal / Home page
│
├── css/
│   └── style.css            # Unified Design System & Responsive layout variables
│
├── js/
│   └── main.js              # Mobile navigation toggler and interactivity scripts
│
├── pages/                   # Isolated inner HTML pages
│   ├── about.html           # About us info
│   ├── contact.html         # Form for administration queries
│   ├── dashboard.html       # Overview of grades, events, and schedules
│   ├── login.html           # Student credentials portal login
│   ├── register.html        # New account signup form
│   ├── timetable.html       # Weekly course schedule table
│   ├── attendance.html      # Subject-wise attendance ratios
│   ├── results.html         # GPA/CGPA results dashboard
│   ├── fees.html            # Semester outstanding bills & dues tracker
│   └── events.html          # Campus workshops, hackathons & registrations
│
├── docs/
│   └── design_artifacts.md  # Information Sitemap, Wireframes, ER diagrams, and REST APIs
│
└── readme.md                # General project overview
```

---

## 👥 User Roles
* **Student:** Principal user. Accesses course tables, grades, bills, registers for hackathons, and manages account.
* **Admin:** Oversees rosters, schedules events, issues billing invoices, and replies to support tickets.

---

## ⚙️ Key Technical Features Implemented

1. **Practical 1 (Setup & Design):** Structured sitemaps, wireframes, database design, and git commits.
2. **Practical 2 (Semantic & Accessible Skeletons):** Focus rings on inputs, skip-to-content links, breadcrumb tracks on internal pages, semantic tags (`<header>`, `<nav>`, `<main>`, `<article>`, `<aside>`, `<footer>`), and linked inputs with `<label>`.
3. **Practical 3 (CSS Flexbox/Grid Responsive Design):** CSS Variables for theming, CSS Flexbox navigation layouts, CSS Grid for dashboard grid systems and responsive tables, media queries adjusting rows/columns for Mobile, Tablet, and Desktop screen widths.