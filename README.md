# Kanchhi Maya Tamang Foundation – Website

A modern, dynamic, responsive website for **Kanchhi Maya Tamang Foundation (KMF)** with a PHP/MySQL backend and Tailwind CSS, inspired by [Prakriti Resource Centre](https://prc.org.np/).

## Features

- **Fully dynamic** – All content from MySQL (pages, strategic areas, programs, publications, news, events, team, partners).
- **Responsive** – Mobile-first layout; works across phones, tablets, and desktops.
- **CMS** – Admin panel to manage settings, pages, strategic areas, programs, publications, news, events, team, and partners.
- **Brand** – KMF logo and colors (dark blue, orange, light green) from the foundation logo.

## Requirements

- PHP 7.4+ (with PDO MySQL)
- MySQL 5.7+ or MariaDB
- Web server (Apache or Nginx)

## Installation

1. **Clone or copy** the project into your web root (e.g. `htdocs/kmf` or document root).

2. **Create the database** and import the schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   Or in MySQL:
   ```sql
   source /path/to/kmf/database/schema.sql
   ```

3. **Configure database** in `config/database.php`:
   - Set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` for your environment.

4. **Configure base URL** in `config/config.php`:
   - If the site is in a subfolder (e.g. `/kmf/`), set:
     `define('BASE_URL', '/kmf/');`

5. **Logo**: Place your KMF logo as `assets/images/logo.jpg` (or set the path in Admin → Settings → Logo URL).

6. **Writable folder** (optional, for future file uploads):
   - Ensure `assets/uploads/` exists and is writable by the web server.

## Default admin login

- **URL**: `yoursite.com/admin/login.php` (or `yoursite.com/kmf/admin/login.php` if in subfolder)
- **Username**: `admin`
- **Password**: `password`

**Important:** Change the admin password after first login (update `admin_users` in the database with a new `password_hash` from `password_hash('your_new_password', PASSWORD_DEFAULT)`).

## Structure

- `index.php` – Home
- `about.php` – Who We Are
- `what-we-do.php` – Strategic Areas (Education, Community, Health)
- `programs.php` – Current & completed projects
- `resources.php` – Publications, reports, articles
- `news.php` – News & media
- `events.php` – Upcoming & past events
- `get-involved.php` – Get involved
- `contact.php` – Contact form (saves to `contact_submissions` table)
- `admin/` – CMS (login, dashboard, settings, pages, areas, programs, publications, news, events, team)

## Implementation plan

See **IMPLEMENTATION_PLAN.md** for the full implementation plan, database schema overview, and deployment notes.
"# Kmf" 
