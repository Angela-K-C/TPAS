## TPAS – Temporary Pass Administration Suite

TPAS is a Laravel 12 application that allows Strathmore University stakeholders to create, review, and verify temporary access passes. The system supports four guards (admin, student, guest, and security) plus QR-code verification and email notifications.

---

### Feature Overview

- **Student Portal (`/login/student`)**
  - Apply for temporary passes, report lost IDs (`StudentController`).
  - Auto-populates visitor details from the authenticated student record.

- **Guest Portal (`/login/guest`)**
  - Passwordless login by email (`GuestController`).
  - Guests can submit visitor applications and view status/QR codes.

- **Admin Console (`/admin/login`)**
  - Approve/reject passes, set validity windows, and trigger email notifications with QR attachments (`TemporaryPassController::update`).
  - View dashboard metrics and expired passes (`AdminController`).

- **Security Desk (`/security/login`)**
  - Guard-only portal to lookup QR-token statuses in real time (`SecurityVerificationController`).

- **Public Verification (`/passes/verify/{token}`)**
  - JSON endpoint embedded in QR payloads for quick scanning.

---

### Tech Stack

- PHP 8.3 / Laravel 12
- MySQL 8.x
- Blade + TailwindCSS + Alpine (bundled via Vite)
- Authentication: Laravel multi-guard (web, university, guest, security)
- Mailing: Laravel Mailables (`WelcomeMail`) + SMTP credentials from `.env`

Optional tooling:
- Node 18+ / npm 10+ for asset compilation
- Redis or database queue (default is sync; configure `QUEUE_CONNECTION` if needed)

---

### 1. Clone & Install Dependencies

```bash
git clone <repo-url>
cd TPAS
composer install
npm install
```

---

### 2. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with real values:

| Key | Description |
| --- | ----------- |
| `APP_URL` | Base URL used in emails and QR links |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Point to your MySQL instance |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_*` | SMTP credentials; defaults log to `storage/logs/laravel.log` |
| `QUEUE_CONNECTION` | `sync` (default) or `database/redis` if you want async mail |

> **Tip:** Approvals trigger emails. If you don’t configure SMTP, keep `MAIL_MAILER=log` so you can inspect outgoing mail in `storage/logs/laravel.log`.

---

### 3. Database Migration & Seeding (Critical)

All controllers expect the latest schema from the `2025_11_*` migrations as well as seeded accounts. Run:

```bash
php artisan migrate --seed
```

This executes:

- Base tables: admins, temporary_passes, guests, email_logs, security_staff
- Column extensions: host/purpose fields (`2025_11_15_*`) and `pass_type` + `details` (`2025_11_20_120000_*`)
- Seeders: `AdminSeeder`, `GuestSeeder`, `SecurityStaffSeeder`, `TemporaryPassSeeder`, `EmailLogSeeder`

If you already had a local database before these commits, run:

```bash
php artisan migrate:fresh --seed
```

Without these migrations you will see `SQLSTATE[42S22]: Unknown column 'pass_type'/'details'` errors when applying for passes or reporting lost IDs.

---

### 4. Storage & Assets

```bash
php artisan storage:link    # allows QR images to be served from /storage
npm run build               # production assets
# or during development
npm run dev
```

---

### 5. Running the Application

```bash
php artisan serve           # http://127.0.0.1:8000
npm run dev                 # optional Vite watcher for Blade/CSS changes
```

Mail is synchronous by default. If you switch to `QUEUE_CONNECTION=database`, also run:

```bash
php artisan queue:work
```

> **Session Reminder:** All guards share a single session cookie (`tpas-session`). Log into different roles using separate browser profiles or incognito windows to avoid unexpected logouts.

---

### 6. Common Workflows

1. **Student applies for a pass**  
   `/applications/create` → form posts to `TemporaryPassController::store`. Missing email/phone fields are filled from the authenticated student/guest profile.

2. **Student reports lost ID**  
   `/report/lost-id` → `StudentController::storeLostId` creates a `temporary_passes` entry with reason `lost_id` and logs the location in `details`.

3. **Admin approval**  
   `/admin/applications/review/{id}` → Admin updates status. Approval auto-generates validity windows, QR token, and sends `WelcomeMail`. Rejection also sends an email.

4. **Security verification**  
   `/security/verify` → Security guard scans the QR and posts token to `/security/lookup`. Public scanners can hit `/passes/verify/{token}` for JSON.

---

### 7. Troubleshooting

- **`SQLSTATE[HY000] [2002] Unknown error while connecting`**  
  Check `.env` database host/port or ensure MySQL is running.

- **`SQLSTATE[42S22]: Unknown column …`**  
  Run `php artisan migrate` in the same environment the app uses. The migration `2025_11_20_120000_add_pass_type_and_details_to_temporary_passes_table.php` now falls back if `purpose` is missing, but the column must still exist.

- **`Command "seed" is not defined`**  
  Use `php artisan db:seed --class=SomeSeeder`, not `php artisan seed`.

- **Emails not sending**  
  Confirm SMTP credentials. With `MAIL_MAILER=log`, mails appear in `storage/logs/laravel.log` (see debug entries in existing logs).

- **Logging into multiple roles kicks you out**  
  Use different browsers or incognito sessions; guards share the same session cookie.

---

### 8. Observability

- Structured JSON logs are written to `storage/logs/structured.log` for approvals, rejections, verification attempts, and email delivery errors.
- Email delivery dashboard is available at `/admin/email-logs` for reviewing pass notification outcomes.

---

### 9. Useful Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan optimize:clear` | Clears config/route/view caches after pulling |
| `php artisan migrate:fresh --seed` | Destroy & recreate database with seed data |
| `php artisan tinker` | Inspect models (e.g., `App\Models\Admin::all()`) |
| `php artisan queue:work` | Process queued mails if you switch from sync driver |
| `php artisan storage:link` | Makes QR/image assets accessible via `/storage` |

---

### 10. Contributing Notes

- Always run tests or key flows (`student` → `passes`, `admin` approvals, `security` lookup) before pushing.
- When adding migrations/seeders, append instructions in sections 3 and 6 of this README so the rest of the team stays in sync.
- Never assume cached config/routes—call `php artisan optimize:clear` when debugging guard or route changes.

With these steps, any teammate can clone TPAS, configure the environment, and use every guard without hitting the schema/auth errors that surfaced after the referenced commit. Happy shipping!
