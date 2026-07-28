# EventPass - Project Implementation TODO List

> **Project Stack:** Laravel 13 (Monolith) + Filament v5 (Dashboard/Multi-Tenancy) + Livewire v5 (Public Registration & QR Mobile Scanner) + TailwindCSS + SQLite / MySQL

---

## 🚀 Phase 1: Environment & Project Setup
- [x] Initialize Laravel 12 project in current directory
- [x] Configure `.env` database, Redis, and Mail driver settings
- [x] Install required Composer packages:
  - `filament/filament` (Latest Version)
  - `spatie/laravel-permission`
  - `simplesoftwareio/simple-qrcode`
  - `barryvdh/laravel-dompdf`
  - `maatwebsite/excel`
- [x] Install Filament Admin & Tenant panels (`php artisan filament:install`)

---

## 🗄️ Phase 2: Database Architecture, Models & Migrations
- [x] Create `companies` migration, model, & factory
- [x] Update `users` migration (add `company_id`, `role`), model, & factory
- [x] Create `events` migration, model, factory & status enums (`Draft`, `Published`, `Closed`, `Finished`)
- [x] Create `ticket_types` migration, model, & factory (VIP, Regular, Speaker, etc.)
- [x] Create `registrations` migration, model, & factory (ticket code, QR code token, custom fields JSON)
- [x] Create `checkins` migration, model, & factory (registration_id, staff_id, device, status)
- [x] Create `notifications` migration & model
- [x] Establish all Eloquent relationships (`Company` hasMany `Users` / `Events`, `Event` hasMany `Registrations` / `TicketTypes`, etc.)
- [x] Set up Database Seeder with SuperAdmin, Sample Company, Events, Ticket Types, and Registrations

---

## 🔐 Phase 3: Roles, Multi-Tenancy & Auth Setup
- [x] Setup Spatie Roles & Permissions (`SuperAdmin`, `CompanyAdmin`, `EventStaff`)
- [x] Configure Filament Multi-Tenancy for Company Workspace scoping (`/app`)
- [x] Configure Super Admin Panel (`/admin`) for global platform management

---

## 📊 Phase 4: Filament Dashboard Resources & Widgets
- [x] **Super Admin Panel (`/admin`):**
  - [x] Company Resource (Manage SaaS subscribers)
  - [x] Subscription Plans Resource (Free, Business, Enterprise)
  - [x] System User Resource & Role Assignment
  - [x] Global Analytics & Activity Log Widgets
- [x] **Company Workspace Panel (`/app`):**
  - [x] Company Profile & Branding Settings Form
  - [x] Team & Staff Management Resource
  - [x] Event Resource (CRUD, Status badge workflow, Google Maps URL, capacity limits)
  - [x] Ticket Type Resource & Custom Fields Builder
  - [x] Attendee Registration Table (Filter, Search, Status update, Excel Export, Print view)
  - [x] Attendance Analytics Dashboard Widgets (Total, Checked-In %, No-shows chart)

---

## 🎟️ Phase 5: Public Event Registration & Ticket Generation Engine
- [x] Build public event Blade layout & registration component (`/events/{event-slug}`)
- [x] Dynamic form renderer based on event ticket categories
- [x] Ticket Code & HMAC-signed QR Code generation logic service (`EVT-YYYY-XXXXXX`)
- [x] PDF Ticket generator service (DomPDF template with company branding, QR code, attendee details)
- [x] Capacity limit validation logic

---

## 📷 Phase 6: QR Code Check-in System (Staff Camera Scanner)
- [x] Build mobile camera scanner component (`/checkin/{eventId}`)
- [x] Integrate HTML5/JS camera scanner library (`html5-qrcode`) with AJAX endpoint
- [x] Secure check-in validation logic:
  - Check ticket existence
  - Validate signed HMAC QR token
  - Verify correct event
  - Check double check-in (`checked_in_at`)
- [x] Success / Error visual modal & sound effects
- [x] Record scan entry in `checkins` table with staff ID and timestamp

---

## ✉️ Phase 7: Queued Mailables & Notifications
- [x] Configure Laravel Queue & Mailables
- [x] `TicketConfirmationMail` Mailable (with PDF ticket attachment & embedded QR code)

---

## 💳 Phase 8: SaaS Subscription Enforcement & Polish
- [x] Seeders & Demo data verification
- [x] End-to-End manual testing of full cycle: Event Creation $\rightarrow$ Registration $\rightarrow$ PDF Mail $\rightarrow$ Staff QR Check-in $\rightarrow$ Analytics
