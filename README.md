# CSU Aparri NROTC – Secure Information Management System

TITLE: Design and Implementation of a secure login and role-based access System for CSU-Aparri NROTC

MEMBERS:
Beltran Juvylyn,
Calpito Angelyn,
Yabes Larry

A Laravel-based web application providing hardened record-keeping and access control for the **Cagayan State University Aparri Naval Reserve Officers Training Corps (NROTC)** unit.

---

## General Objective

Design and implement a secure login and role-based access system for CSU Aparri NROTC to protect system data from unauthorized access, in accordance with OWASP and NIST SP 800-63B guidelines.

---

## Security Architecture

### 1. Authentication System (OWASP A07)
| Control | Implementation |
|---|---|
| Password hashing | **bcrypt** via Laravel's `hashed` cast — plain-text passwords never stored |
| Brute-force protection | Account locked for **15 minutes** after **5** consecutive failed attempts |
| Session fixation prevention | `session()->regenerate()` called on every successful login |
| Session timeout | Auto-logout after **30 minutes** of inactivity (`SessionTimeout` middleware) |
| CSRF protection | Laravel `VerifyCsrfToken` middleware on all POST/PATCH routes |
| Input validation | Strict rules on all request inputs — prevents SQL Injection (OWASP A03) |
| User enumeration prevention | Generic error messages for unknown email / inactive accounts |

### 2. Role-Based Access Control — RBAC (OWASP A01)
| Role | Permissions |
|---|---|
| **Administrator** | Create/deactivate accounts, unlock locked users, view all statistics, configure system |
| **Officer** | View cadet roster, mark attendance (future), record grades (future) — read-only |
| **Cadet** | View own profile, own attendance, own grades — cannot see other cadets' data |

RBAC is enforced by the `EnsureRole` middleware, applied per route group.

### 3. Data Protection (OWASP A02)
- **bcrypt** password hashing (work factor 12 by default in Laravel)
- Sessions stored in the database driver with encrypted payloads (`SESSION_ENCRYPT=true` recommended in production)
- TLS/HTTPS must be enforced at the web server or load balancer level in production

---

## Project Structure

```
app/
+-- Http/
¦   +-- Controllers/
¦   ¦   +-- Auth/AuthController.php        # Login, logout, lockout logic
¦   ¦   +-- Admin/DashboardController.php  # Admin CRUD + account management
¦   ¦   +-- Officer/DashboardController.php
¦   ¦   +-- Cadet/DashboardController.php
¦   +-- Middleware/
¦       +-- EnsureRole.php                 # RBAC enforcement
¦       +-- SessionTimeout.php            # Idle-session expiry
+-- Models/User.php                        # Role helpers, lockout helpers
database/
+-- migrations/
¦   +-- 0001_01_01_000000_create_users_table.php
¦   +-- 2026_03_24_000001_add_nrotc_fields_to_users_table.php
+-- seeders/DatabaseSeeder.php             # Default admin/officer/cadet accounts
resources/views/
+-- layouts/app.blade.php                  # Shared authenticated layout
+-- auth/login.blade.php                   # Secure login page
+-- admin/dashboard.blade.php
+-- admin/create-user.blade.php
+-- officer/dashboard.blade.php
+-- cadet/dashboard.blade.php
routes/web.php                             # All routes with middleware groups
bootstrap/app.php                          # Middleware alias registration
```

---

## Quick Start

### Prerequisites
- PHP >= 8.2
- Composer
- A supported database (MySQL / PostgreSQL / SQLite)
- Node.js + npm (for asset compilation)

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file and generate application key
cp .env.example .env
php artisan key:generate

# 3. Configure your database in .env
#    DB_CONNECTION=mysql
#    DB_DATABASE=nrotc_db
#    DB_USERNAME=...
#    DB_PASSWORD=...

# 4. Run migrations
php artisan migrate

# 5. Seed default accounts
php artisan db:seed

# 6. (Optional) Compile front-end assets
npm install && npm run build

# 7. Start the development server
php artisan serve
```

Open http://localhost:8000 — you will be redirected to /login.

### Default Credentials (change immediately in production)

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@nrotc.csu.edu.ph` | `Admin@NROTC2026!` |
| Officer | `officer@nrotc.csu.edu.ph` | `Officer@NROTC2026!` |
| Cadet | `cadet@nrotc.csu.edu.ph` | `Cadet@NROTC2026!` |

---

## Production Hardening Checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false` in `.env`
- [ ] `SESSION_ENCRYPT=true` in `.env`
- [ ] Enforce HTTPS/TLS at the web server (nginx / Apache)
- [ ] Set `SECURE_COOKIES=true` and `SESSION_SAME_SITE=strict`
- [ ] Change all default seeded passwords
- [ ] Set up database backups
- [ ] Configure `LOG_LEVEL=warning` and monitor `storage/logs/`
- [ ] Review and tighten Content Security Policy headers
