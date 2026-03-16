# Kanchhi Maya Tamang Foundation – Website Implementation Plan

## 1. Overview

Build a **modern, fully dynamic, responsive** website for **Kanchhi Maya Tamang Foundation (KMF)** inspired by [Prakriti Resource Centre (PRC)](https://prc.org.np/), using **HTML5**, **PHP**, **MySQL**, **Tailwind CSS**, and an **admin CMS** for content management.

---

## 2. Tech Stack

| Layer        | Technology |
|-------------|------------|
| Frontend    | HTML5, Tailwind CSS (CDN + custom), vanilla JS |
| Backend     | PHP 7.4+   |
| Database    | MySQL 5.7+ / MariaDB |
| CMS         | Custom PHP admin panel (no framework) |
| Assets      | Logo (KMF), images, favicon |

---

## 3. Site Structure (PRC-like)

### 3.1 Public Sections

| Section        | URL / File       | Content Source        |
|----------------|------------------|------------------------|
| Home           | `index.php`      | DB: hero, mission, vision, goal, featured items |
| Who We Are     | `about.php`      | DB: about text, core values, team, partners |
| What We Do     | `what-we-do.php` | DB: strategic areas (education, health, community) |
| Our Programs   | `programs.php`   | DB: current + completed projects |
| Resources      | `resources.php`  | DB: publications, reports, articles/blogs |
| News & Media   | `news.php`       | DB: news, media, photo/video gallery |
| Events         | `events.php`     | DB: past + upcoming events |
| Get Involved   | `get-involved.php`| DB: opportunities, vacancies, contact |
| Contact        | `contact.php`    | DB: contact info, form submissions |

### 3.2 Admin CMS

| Area           | URL              | Purpose |
|----------------|------------------|---------|
| Login          | `admin/login.php`| Auth    |
| Dashboard      | `admin/index.php`| Overview, quick stats |
| Pages          | `admin/pages.php`| Edit homepage, about, etc. |
| Strategic Areas| `admin/areas.php`| What We Do items |
| Programs       | `admin/programs.php` | Projects |
| Publications   | `admin/publications.php` | Resources |
| News           | `admin/news.php` | News items |
| Events         | `admin/events.php` | Events |
| Team/Partners  | `admin/team.php` | Team & partners |
| Settings       | `admin/settings.php` | Site title, contact, logo, social links |
| Logout         | `admin/logout.php` | Session destroy |

---

## 4. Database Schema (MySQL)

### 4.1 Core Tables

- **`settings`** – key-value (site_name, tagline, email, phone, address, logo_url, social links, mission, vision, goal).
- **`pages`** – id, slug, title, content (HTML), meta_description, updated_at.
- **`strategic_areas`** – id, title, slug, excerpt, content, icon, sort_order, is_active.
- **`programs`** – id, title, slug, excerpt, content, type (current/completed), image_url, sort_order, is_active, created_at.
- **`publications`** – id, title, slug, excerpt, type (publication/report/article), file_url, image_url, published_at, sort_order.
- **`news`** – id, title, slug, excerpt, content, image_url, published_at, is_featured.
- **`events`** – id, title, slug, excerpt, event_date, end_date, venue, content, image_url, type (upcoming/past).
- **`team`** – id, name, role, bio, image_url, type (board/staff), sort_order.
- **`partners`** – id, name, logo_url, link_url, sort_order.
- **`gallery`** – id, title, image_url, category (photo/video), sort_order.
- **`contact_submissions`** – id, name, email, subject, message, created_at.
- **`admin_users`** – id, username, password_hash, email, created_at.

### 4.2 Relationships

- All content tables use `slug` for SEO-friendly URLs.
- Programs linked by `type`; events by `type` and dates; news by `published_at`.

---

## 5. File Structure

```
kmf/
├── assets/
│   ├── css/
│   │   └── custom.css
│   ├── js/
│   │   └── main.js
│   ├── images/
│   │   └── logo.jpg (KMF logo)
│   └── uploads/          (CMS uploads: images, PDFs)
├── admin/
│   ├── index.php         (dashboard)
│   ├── login.php
│   ├── logout.php
│   ├── pages.php
│   ├── areas.php
│   ├── programs.php
│   ├── publications.php
│   ├── news.php
│   ├── events.php
│   ├── team.php
│   ├── settings.php
│   └── includes/
│       ├── header.php
│       └── footer.php
├── config/
│   ├── database.php      (DB connection)
│   └── config.php        (base URL, timezone, etc.)
├── includes/
│   ├── header.php        (site header + nav)
│   ├── footer.php        (site footer)
│   ├── db.php            (optional PDO wrapper)
│   └── functions.php     (helpers, sanitize, etc.)
├── index.php             (home)
├── about.php
├── what-we-do.php
├── programs.php
├── resources.php
├── news.php
├── events.php
├── get-involved.php
├── contact.php
├── .htaccess             (optional: clean URLs)
├── IMPLEMENTATION_PLAN.md
├── database/
│   └── schema.sql        (full DB schema + seed)
└── README.md
```

---

## 6. Design (Tailwind + Brand)

- **Reference:** [PRC](https://prc.org.np/) – clean, professional, section-based.
- **KMF brand (from logo):**
  - Primary: **dark blue** (header, footer, accents).
  - Secondary: **orange** (CTAs, highlights).
  - Tertiary: **light green** (cards, sections).
  - Neutral: **white**, light gray backgrounds.
- **Tailwind:** Use CDN for quick start; optional build later.
- **Responsive:** Mobile-first; breakpoints: sm, md, lg, xl, 2xl.
- **Components:** Navbar (sticky, mobile menu), hero, cards, sections, footer with columns, forms.

---

## 7. CMS Features

- **Auth:** Session-based login (username + password); password hashing with `password_hash()`.
- **CRUD:** Create, read, update, delete for all content types.
- **Media:** Simple file upload (images, PDFs) into `assets/uploads/`.
- **Ordering:** `sort_order` for strategic areas, programs, team, partners.
- **No WYSIWYG required initially:** Textarea for content; optional TinyMCE/CKEditor later.

---

## 8. Responsive & Accessibility

- Semantic HTML5 (header, nav, main, section, article, footer).
- Tailwind responsive classes on all major blocks.
- Touch-friendly buttons and nav on mobile.
- Forms: labels, placeholders, basic validation (HTML5 + PHP).
- Alt text for images (stored in DB or settings).

---

## 9. Implementation Phases

| Phase | Tasks |
|-------|--------|
| **1** | DB schema, config, connection; seed default content and one admin user. |
| **2** | Layout: header, footer, Tailwind; home (hero, mission/vision/goal, strategic areas preview). |
| **3** | Public pages: about, what we do, programs, resources, news, events, get involved, contact. |
| **4** | Admin: login, dashboard; CRUD for settings, pages, areas, programs, publications, news, events, team. |
| **5** | Contact form (save to DB, optional email); file uploads; polish responsive + SEO (meta, slugs). |

---

## 10. Deployment Notes

- PHP 7.4+ with MySQLi or PDO.
- Document root points to `kmf/` (or public subfolder if used).
- `assets/uploads/` writable by web server.
- Strong password for admin; HTTPS recommended.
- Optional: `.htaccess` for clean URLs (e.g. `/about` → `about.php`).

---

## 11. Success Criteria

- [ ] Site mirrors PRC-like structure and is fully dynamic (content from MySQL).
- [ ] CMS allows editing of all main content types without touching code.
- [ ] Responsive on mobile, tablet, desktop.
- [ ] KMF logo and brand colors applied consistently.
- [ ] Contact form stores submissions (and optionally sends email).
