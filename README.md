<div align="center">

# SmartBooking

**A multi-tenant SaaS appointment booking platform built with Laravel 13, Inertia.js v2, and Vue 3.**

Give any service business — salons, clinics, studios, consultants — its own branded booking page, staff roster, and availability calendar, all from a single codebase with complete tenant isolation.

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.4-4FC08D?style=flat&logo=vue.js&logoColor=white)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-9553E9?style=flat)](https://inertiajs.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

</div>

---

## Overview

SmartBooking is a multi-tenant booking SaaS where each business operates as an isolated tenant under a single Laravel installation. A business owner signs up, completes an onboarding flow, and instantly gets a public booking page (`/b/{slug}`), a staff and service catalog, an availability schedule, and a live calendar — while a Super Admin layer oversees every tenant, platform-wide revenue, and system health from one dashboard.

The project is built to explore patterns that most CRUD tutorials skip: tenant-scoped middleware, role-based route protection for three distinct user types, and an admin layer that sits above the tenants themselves rather than inside them.

> **Status:** Actively in development. Core booking, staff, and super-admin modules are functional; see [Roadmap](#roadmap) for what's next.

## Recently Added

- Tenant-isolation feature coverage with `TenantIsolationTest`
- Proper foreign-key setup for bookings, businesses, services, and tenant owners
- `users.business_id` support for owner-to-business linkage
- Business settings page and update flow for operational profile management

## Screenshots

<!-- Add screenshots or a short demo GIF here, e.g.:
| Public Booking Page | Business Calendar | Super Admin Oversight |
|---|---|---|
| ![booking](docs/screenshots/booking-page.png) | ![calendar](docs/screenshots/calendar.png) | ![superadmin](docs/screenshots/superadmin.png) |
-->
*Screenshots coming soon.*

## Key Features

### 🌐 Public / Guest Layer
- Branded, slug-based booking pages per business (`/b/{business-slug}`)
- Real-time available time-slot lookup based on staff schedule and service duration
- Guest checkout — customers can book a service without needing an account first

### 🙋 Customer Portal ("My Agenda")
- Personal dashboard of upcoming and past bookings
- Self-service booking cancellation
- Business discovery hub to browse available services

### 🧑‍💼 Business Owner / Admin Suite
- **Onboarding wizard** — guided first-time setup that provisions a new tenant
- **Service catalog** — CRUD for services with pricing, duration, and active/inactive status
- **Service add-ons** — upsell items attached to a base service
- **Staff roster** — manage team members assigned to bookings
- **Availability rules** — per-day open/close windows that drive slot generation
- **Booking management** — status updates (pending → confirmed → completed/cancelled) and a full calendar view (FullCalendar: day/week/month grids)
- **Business settings** — branding, contact info, and identity customization

### 🛡️ Super Admin Suite (Platform Oversight)
- Global KPIs: total users, active vs. suspended businesses
- Platform-wide gross revenue and a rolling 30-day revenue trend chart (Chart.js)
- Entity management — view every tenant and toggle a business active/suspended
- Financials dashboard and an audit log for platform-level activity
- System configuration panel

### 🔐 Access Control
Three distinct roles (`customer`, `owner`, `super_admin`) are enforced through dedicated middleware rather than a single generic gate — each layer (tenant, business-admin, super-admin) is independently protected and cannot be reached by the wrong role, even by URL guessing.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend framework | Laravel 13 (PHP 8.3) |
| Frontend ↔ backend bridge | Inertia.js v2 (server-driven SPA, no separate REST layer for the UI) |
| Frontend framework | Vue 3 (Composition API) |
| Styling | Tailwind CSS |
| Calendar UI | FullCalendar (day grid, time grid, interaction plugins) |
| Charts / analytics | Chart.js + vue-chartjs |
| Auth scaffolding | Laravel Breeze |
| API tokens (future use) | Laravel Sanctum |
| Build tool | Vite |
| Database | SQLite for local development; MySQL-compatible for production |

## Architecture: How Multi-Tenancy Works

SmartBooking uses **row-level multi-tenancy** — every tenant (business) shares the same database and application, scoped by a `business_id` foreign key, rather than a separate database per tenant. This keeps the app simple to deploy while still fully isolating each business's data.

```
Request → auth + verified
        → EnsureBusinessIsSetup   (forces onboarding if an owner has no business yet)
        → EnsureTenantAccess      (blocks access without a business, binds tenant_id to the request)
        → admin middleware        (owner-only routes: services, staff, bookings, calendar, settings)
        → super_admin middleware  (platform-wide routes: entities, financials, audit, config)
```

Each of the three access layers — **public/guest**, **authenticated tenant (owner/customer)**, and **super admin** — is checked independently, so a customer account can never reach admin routes and a business owner can never reach another tenant's data or the platform-oversight layer.

### Core Data Model

| Model | Purpose |
|---|---|
| `User` | Auth identity; carries the `customer` / `owner` / `super_admin` role |
| `Business` | A tenant — one owner, one slug, one public booking page |
| `Service` | A bookable offering with price and duration, scoped to a business |
| `ServiceAddon` | Optional upsell attached to a service |
| `Staff` | Team members a business can assign to bookings |
| `Availability` | Per-day open/close windows used to compute free slots |
| `Booking` | The core transaction — links a customer, service, staff member, and time slot |

### Tenant Linkage Notes

- `users.business_id` is used to associate an owner with a business tenant
- `businesses.user_id` points to the owning user
- `services.business_id` and `bookings.business_id` keep tenant data scoped to the correct business
- Tenant-scoped models rely on the shared `BelongsToTenant` trait and `TenantScope`

## Getting Started

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & npm
- SQLite (bundled) or MySQL

### Installation

```bash
# Clone the repository
git clone https://github.com/mahmudpial/laravel-inertia-booking-saas.git
cd laravel-inertia-booking-saas

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database (SQLite by default)
touch database/database.sqlite
php artisan migrate

# Build frontend assets
npm run build
```

Or use the bundled Composer script to do the above in one step:

```bash
composer run setup
```

### Running Locally

```bash
composer run dev
```

This starts the PHP server, queue listener, log watcher (Pail), and the Vite dev server together.

The app will be available at `http://localhost:8000`.

### Running Tests

```bash
php artisan test
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Booking, Service, Staff, Calendar, SuperAdmin, etc.
│   └── Middleware/      # Tenant isolation & role-based access control
└── Models/              # Business, Booking, Service, Staff, Availability, ServiceAddon

resources/js/
├── Pages/
│   ├── Admin/           # Owner-facing modules (Services, Staff, Calendar, Settings)
│   ├── Admin/SuperAdmin/# Platform oversight (Entities, Financials, Audit, Config)
│   ├── Customer/        # Customer dashboard & discovery hub
│   └── Booking/         # Public booking flow
└── Layouts/

database/migrations/     # Tenant, service, staff, availability & booking schema
```

## Roadmap

- [ ] Payment gateway integration for paid bookings
- [ ] Email/SMS reminders for upcoming appointments
- [ ] Recurring booking support
- [ ] Public API for third-party integrations
- [x] Automated feature test coverage for booking & tenant-isolation logic

## Author

**Pial Mahmud**
Full-Stack Developer (Laravel & Vue 3)
[GitHub](https://github.com/mahmudpial) · [pialmahmud80@gmail.com](mailto:pialmahmud80@gmail.com)

## License

Licensed under the [MIT License](LICENSE).
