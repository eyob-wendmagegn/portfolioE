# Eyob Wendmamagegn — Full-Stack Developer Portfolio & CMS

A modern, high-performance, responsive Developer Portfolio and Content Management System built for **Eyob Wendmamagegn**, a junior full-stack developer and Laravel/Vue.js specialist based in Ethiopia.

The project is architected with a decoupled **Laravel REST API** backend and a reactive **Vue.js 3** SPA frontend, backed by an enterprise **PostgreSQL** database.

---

## 1. Architecture & Technology Stack

### Backend
- **Framework:** Laravel 12.x (PHP 8.2+)
- **Database:** PostgreSQL (`port_db`)
- **Authentication:** Laravel Sanctum (Token-based API authentication)
- **Architecture:** RESTful API (`/api/v1`), Eloquent ORM, Form Requests, API Resources, Model Policies, Storage link
- **Automated Testing:** PHPUnit / Pest Feature & Unit Tests

### Frontend
- **Framework:** Vue.js 3 (Composition API with `<script setup>`)
- **Build Tool:** Vite
- **Routing:** Vue Router 4 (with route-level authentication guards)
- **State Management:** Pinia
- **Styling & Design System:** Tailwind CSS (Midnight-blue/dark navy theme & crisp light mode)
- **Icons:** Lucide Icons (`lucide-vue-next`)
- **HTTP Client:** Axios (interceptor handles bearer tokens & 401 redirects)

---

## 2. Key Features

### Public Portfolio Website (`/`)
1. **Hero Section:**
   - Typewriter animation rotating through professional titles (*Laravel Developer, Vue.js Developer, Full-Stack Web Developer, Backend Developer, Web Application Developer*).
   - "Explore My Work" and "Download CV" action buttons.
   - Profile frame with blue-to-purple gradient border, subtle ambient glow, and floating technology badges (*Laravel, Vue.js, PostgreSQL, REST API*).
   - Active availability indicator: *"Available for Work & Collaboration"*.
2. **Flagship Project Showcase — Library Management System:**
   - **2-column by 2-row grid on desktop:**
     - Top-Left: **Administrator Dashboard** (Rose badge)
     - Top-Right: **Librarian Dashboard** (Indigo badge)
     - Bottom-Left: **Teacher Dashboard** (Emerald badge)
     - Bottom-Right: **Student Dashboard** (Amber badge)
   - Real high-resolution screenshot viewer with lightbox zoom.
   - Dynamic placeholder fallback with role UI previews until real screenshots are uploaded.
3. **Featured Projects & Work Gallery:**
   - Dynamic projects fetched from PostgreSQL database.
   - Category filtering (*Full-Stack Applications, Laravel Projects, Vue.js Applications, Backend & REST APIs, Academic Projects*).
   - Real-time search by title, description, or technology stack.
   - Comprehensive Project Modal with screenshot gallery, live demo, and GitHub repository links.
4. **Skills & Stack Section:**
   - Grouped into *Backend Development*, *Frontend Development*, and *Additional Technologies*.
   - Editable titles, proficiency levels, and descriptions.
5. **About Me & Education:**
   - Highlights academic background at **Woldia University**, technical interests, and career philosophy.
6. **Services & Capabilities:**
   - Custom cards for Laravel Web Applications, Vue.js SPAs, REST APIs, Database Design, Website Implementation, and Bug Fixing.
7. **Contact / Collaboration Section:**
   - Interactive contact form with client-side & server-side validation.
   - Rate-limited submission saving directly to `contact_messages` table.
   - Configurable email (`eyobnigata18@gmail.com`), phone, location, and social links.
8. **Dark & Light Mode:**
   - Persisted in `localStorage` and respects system preferences.
   - Zero flash during load with accessible contrast ratios.

---

### Private Admin CMS (`/admin/login` -> `/admin/dashboard`)
1. **Secure Admin Authentication:**
   - Dedicated login card inspired by dark grid aesthetics.
   - Initial administrator account: `eyobnigata18@gmail.com`.
   - Rate limiting, Sanctum personal access tokens, and backend role verification (`EnsureUserIsAdmin`).
2. **Dashboard Overview:**
   - Real-time live statistics from the database (Total projects, featured, published vs draft, total skills, unread messages).
   - Recent inquiry messages and recently updated projects.
3. **Projects Management:**
   - Complete CRUD operations (create, read, update, delete).
   - Cover image upload and multiple gallery screenshots upload.
   - Toggle featured status, publish/draft visibility, and custom slug generation.
4. **LMS Dashboard Screenshots Manager:**
   - Dedicated upload and replacement manager for all four Library Management System roles.
   - Ability to remove screenshots and revert to placeholder mode anytime.
5. **Skills & Services Management:**
   - Create, edit, reorder, and toggle visibility for skills and service offerings.
6. **Contact Message Management:**
   - View submissions, mark read/unread, search inquiries, and delete.
7. **Portfolio Content Editor:**
   - Update Hero copy, animated role titles, bio, and social links without editing source code.
   - Upload/replace profile photo (hero avatar).
8. **Site Settings & CV:**
   - Upload, preview, and replace downloadable PDF Curriculum Vitae.

---

## 3. Database Setup & PostgreSQL Configuration

The application is configured to connect to PostgreSQL on database `port_db`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=port_db
DB_USERNAME=postgres
DB_PASSWORD=postEW12
```

### Initial Administrator Account
- **Email:** `eyobnigata18@gmail.com`
- **Password:** Configured via `ADMIN_PASSWORD` in `backend/.env` (default during seeding: `Password123!`)

---

## 4. Installation & Local Development Guide

### Step 1: Clone or Navigate to Project
```bash
cd "d:\Agreate Folder\projects\laravel\portfolio"
```

### Step 2: Configure Backend (Laravel)
```bash
cd backend
# Install dependencies (if not already installed)
composer install

# Verify .env file has DB_CONNECTION=pgsql and DB_DATABASE=port_db
# Run migrations and seed default content and administrator account:
php artisan migrate:fresh --seed

# Create storage symlink for uploaded images:
php artisan storage:link

# Start the Laravel REST API server:
php artisan serve
```
The backend API will run on `http://localhost:8000`.

### Step 3: Configure Frontend (Vue 3 + Vite)
In a separate terminal:
```bash
cd "d:\Agreate Folder\projects\laravel\portfolio\frontend"
# Install npm packages (if not already installed)
npm.cmd install

# Start Vite development server:
npm.cmd run dev
```
The frontend application will run on `http://localhost:5173`.

---

## 5. Automated Tests

The backend includes comprehensive automated feature tests covering public endpoints, admin authentication, rate limiting, and project CRUD:

```bash
cd backend
php artisan test
```

### Test Coverage Highlights
- `PublicApiTest`: Verifies public portfolio payload, category filtering, single project resolution, and LMS four-role dashboards.
- `AdminAuthTest`: Verifies valid credentials login, token generation, 401 unauthorized rejection, and protected dashboard access.
- `AdminProjectCrudTest`: Verifies project creation, updates, and deletion with database integrity.
- `ContactFormTest`: Verifies contact form validation, rate limiting, and persistence to `contact_messages`.

---

## 6. API Route Reference

### Public Endpoints
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/portfolio` | Unified bundle for initial page hydration |
| `GET` | `/api/v1/projects` | Filter projects by category, search keyword, or featured |
| `GET` | `/api/v1/projects/{slug}` | Get single project details by slug |
| `GET` | `/api/v1/skills` | List all visible skills grouped by category |
| `GET` | `/api/v1/services` | List active service offerings |
| `GET` | `/api/v1/library-dashboards` | List four LMS dashboard screenshots & metadata |
| `GET` | `/api/v1/site-settings` | Public site metadata & social links |
| `POST` | `/api/v1/contact` | Submit contact inquiry (Rate limited) |
| `GET` | `/api/v1/cv/download` | Direct download endpoint for uploaded CV |

### Admin Endpoints (Protected by Sanctum Token)
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/v1/admin/login` | Administrator authentication |
| `POST` | `/api/v1/admin/logout` | Revoke current token |
| `GET` | `/api/v1/admin/me` | Authenticated administrator profile |
| `PUT` | `/api/v1/admin/profile` | Update admin name, email, or password |
| `GET` | `/api/v1/admin/dashboard/stats` | Real-time live dashboard metrics |
| `GET/POST` | `/api/v1/admin/projects` | List projects / create new project |
| `PUT/DELETE` | `/api/v1/admin/projects/{id}` | Update / delete existing project |
| `POST` | `/api/v1/admin/projects/{id}/images` | Upload project gallery screenshot |
| `POST` | `/api/v1/admin/library-dashboards/{id}` | Update role card & upload real screenshot |
| `DELETE` | `/api/v1/admin/library-dashboards/{id}/image`| Revert role screenshot to placeholder |
| `GET/POST` | `/api/v1/admin/skills` | Manage skills CRUD |
| `GET/POST` | `/api/v1/admin/services` | Manage services CRUD |
| `GET/POST` | `/api/v1/admin/education` | Manage education CRUD |
| `GET` | `/api/v1/admin/messages` | View contact messages inbox |
| `PATCH` | `/api/v1/admin/messages/{id}/toggle-read` | Toggle read/unread flag |
| `DELETE` | `/api/v1/admin/messages/{id}` | Delete inquiry |
| `POST` | `/api/v1/admin/settings` | Save portfolio text & social links |
| `POST` | `/api/v1/admin/settings/avatar` | Upload/replace profile photo |
| `POST` | `/api/v1/admin/settings/cv` | Upload/replace CV document |

---

## 7. Production Build & Deployment

### Production Frontend Build
```bash
cd frontend
npm.cmd run build
```
Generates optimized, minified production assets into `frontend/dist/`.

### Web Server (Nginx / Apache)
1. Point your web server's public root for the frontend to `frontend/dist`.
2. Configure API proxy or domain for Laravel `backend/public`.
3. Set `APP_ENV=production`, `APP_DEBUG=false`, and configure `APP_URL` and `FRONTEND_URL` in `backend/.env`.
4. Ensure `php artisan storage:link` has been executed.
