# Medi-O v2 — Secure Online Pharmacy with QR-Based Prescription Verification

A complete rebuild of the Medi-O pharmacy platform. Plain **PHP 8.2 + MySQL/MariaDB +
HTML/CSS/JS (Bootstrap 5)** — no framework, runs directly on XAMPP.

## ✨ Features

**Customers**
- Shop 80+ products with live search, category filters, price slider, sorting, pagination
- AJAX cart, checkout with **PayHere sandbox** (or built-in demo gateway) + cash on pickup/delivery
- Upload prescriptions (JPG/PNG/WEBP/PDF) → pharmacist review → status notifications
- **Personal QR card** — pharmacist scans it and hands over your ready orders & medicines
- Unique QR on every order and a public **authenticity-check QR** on every prescription
- **Medication tracker** with WhatsApp / email dose reminders and adherence history
- **Live chat** with the pharmacy team (plus one-tap WhatsApp)
- Login with **email, mobile number, or Google account**; password reset by email
- In-app notification bell, order tracking timeline, printable QR card
- Fully responsive — phone, tablet, desktop

**Pharmacist dashboard** (`staff/`)
- Live queue: prescriptions to review, orders to prepare, unread chats
- **Camera QR scanner** (html5-qrcode) for customer cards / order QRs
- One-click approve / reject / ready — customer is notified in-app + email + WhatsApp
- Order board with status updates, chat inbox, medication-plan assignment with adherence bars
- WhatsApp outbox: Cloud API sending, or logged messages + wa.me deep links in demo mode

**Admin** (`admin/`) — everything pharmacists have, plus:
- Analytics: revenue/orders chart (Chart.js), prescription stats, top sellers
- Product & stock management (soft delete, image upload, Rx flag, categories)
- User management (roles, delete), contact inquiries, pharmacy settings

## 🚀 Quick start (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\medio`
2. Start **Apache** and **MySQL** in the XAMPP control panel
3. Import the database — either via phpMyAdmin (import `database/medio.sql`) or:
   ```bash
   C:\xampp\mysql\bin\mysql -u root < database/medio.sql
   ```
4. Open **http://localhost/medio/**

That's it. PHPMailer + the QR library are bundled in `vendor/`
(if you ever delete it, run `composer install`).

### Demo accounts

| Role | Login | Password |
|---|---|---|
| Admin | `admin@medi-o.lk` | `Admin@123` |
| Pharmacist | `pharmacist@medi-o.lk` | `Pharma@123` |
| Customer | `demo@medi-o.lk` *(or phone `0771234567`)* | `Demo@123` |

> ⚠️ Change these passwords before putting the system online.

## 🔌 Optional integrations (all free)

Copy `config.local.example.php` → `config.local.php` and fill in only what you need.
Every feature has a **demo mode** and works without any keys.

| Integration | Without keys | How to get keys (free) |
|---|---|---|
| **Google Sign-In** | Button hidden | [console.cloud.google.com](https://console.cloud.google.com) → APIs & Services → Credentials → *Create OAuth client ID (Web)* → add `http://localhost` to Authorised JavaScript origins → paste `GOOGLE_CLIENT_ID` |
| **PayHere payments** | Built-in demo gateway simulates success/failure | [sandbox.payhere.lk](https://sandbox.payhere.lk) → create sandbox merchant → `PAYHERE_MERCHANT_ID` + `PAYHERE_MERCHANT_SECRET` (test card: 4916 2175 0161 2292) |
| **WhatsApp reminders** | Messages stored in staff → WhatsApp outbox, deliverable manually via wa.me links | [developers.facebook.com](https://developers.facebook.com) → My Apps → Create App → WhatsApp → API Setup → copy temp `WHATSAPP_TOKEN` + `WHATSAPP_PHONE_ID` (free tier: 1000 conversations/month) |
| **Email (Gmail)** | Emails written to `logs/mail.log` | Google Account → Security → 2-Step Verification → App passwords → `SMTP_USER` + `SMTP_PASS` |

### Medicine reminder scheduler

Reminders are dispatched by `cron/send-reminders.php`. Set it to run every 5 minutes with
**Windows Task Scheduler**:

- Program: `C:\xampp\php\php.exe`
- Arguments: `C:\xampp\htdocs\medio\cron\send-reminders.php`
- Trigger: Daily → repeat task every 5 minutes

(For demos, staff can also click **"Run reminder dispatch now"** on the Medications page.)

## 🔒 Security notes

- All SQL uses prepared statements (PDO) — no string-built queries
- Passwords: bcrypt: reset tokens: SHA-256-hashed, 1-hour expiry
- CSRF tokens on every form and API call; sessions are HttpOnly + SameSite
- Role guards on every staff/admin page and API
- Prescription files are stored behind a permission-checked streamer (`file.php`) —
  the folder itself denies direct access
- Uploads are MIME-validated, size-limited, and renamed to random names;
  the uploads folder cannot execute code
- QR tokens are 160-bit random values — they contain no personal data
- Secrets live in `config.local.php` (gitignored), never in code

## 📁 Project layout

```
medio/
├─ index.php, shop.php, product.php, cart.php, checkout.php, payment*.php
├─ prescriptions.php, medications.php, chat.php, my-qr.php, orders.php, order.php
├─ login.php, signup.php, forgot/reset-password.php, profile.php
├─ verify-prescription.php      ← public QR authenticity check
├─ file.php                     ← permission-checked prescription streamer
├─ auth/google.php              ← Google Sign-In callback
├─ api/                         ← JSON: cart, chat, search, notifications, PayHere IPN
├─ staff/                       ← pharmacist dashboard (scan, verify, queue, orders, chat…)
├─ admin/                       ← analytics, products, users, inquiries, settings
├─ components/                  ← shared header/footer/product-card layouts
├─ includes/                    ← db, auth, helpers, mailer, whatsapp, qr, payhere
├─ cron/send-reminders.php      ← reminder dispatcher
├─ database/medio.sql           ← full database (schema + seed + products)
└─ assets/                      ← css/app.css, js/app.js, images
```
