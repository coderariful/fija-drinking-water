INSTRUCTIONS — Fiji Drinking Water (local dev + maintenance)

Overview
--------
This repository is a Laravel-based web application for managing Fiji Drinking Water operations (customers, sales, purchases, SMS notifications, etc.). These instructions explain how to set up a local development environment on Windows (cmd.exe), run the app, run tests, and troubleshoot common problems.

Assumptions
-----------
- PHP 8.0+ (recommended 8.1/8.2) and Composer installed and on PATH.
- Node.js (16+) and npm installed.
- A MySQL-compatible database available locally.
- The project is a typical Laravel app (artisan, `config/*.php`, `routes/`, `resources/` present).

Quick checklist (high level)
---------------------------
- [ ] Install system dependencies: PHP, Composer, Node
- [ ] Clone repo and copy `.env` from `.env.example`
- [ ] Install PHP & JS dependencies (`composer install`, `npm install`)
- [ ] Create database and set `.env` DB_* values
- [ ] Run `php artisan key:generate`, migrate, seed
- [ ] Link storage, run dev server / vite

Preparation — Windows (cmd.exe)
-------------------------------
1. Open a Command Prompt as a normal user (or Administrator when adjusting system services).
2. Change directory to the project root:

```cmd
cd /d E:\codes\fijadrinkingwater\fijadrinkingwater
```

Environment (.env)
-------------------
Copy the example environment (if present) and update required keys:

```cmd
copy .env.example .env
```

Edit `.env` and set the following at minimum:
- APP_NAME, APP_URL
- APP_ENV, APP_DEBUG
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- MAIL_*, SMS_* (see `config/mail.php` and `config/sms.php`)
- QUEUE_CONNECTION (e.g., database or sync)

Then generate app key:

```cmd
php artisan key:generate
```

Install dependencies
--------------------
Install PHP dependencies with Composer:

```cmd
composer install --no-interaction --prefer-dist
```

Install Node dependencies and build assets (dev):

```cmd
npm install
npm run dev
```

For production assets:

```cmd
npm run build
```

Database setup
--------------
Create an empty database via MySQL tools (MySQL Workbench, phpMyAdmin, or mysql CLI). Then run migrations and optional seeders:

```cmd
php artisan migrate
php artisan db:seed
```

If the project uses specific seeders, run them explicitly:

```cmd
php artisan db:seed --class=Database\\Seeders\\SomeSpecificSeeder
```

Storage and permissions
-----------------------
Create the storage symlink so uploaded files are available publicly:

```cmd
php artisan storage:link
```

On Windows you may not need to adjust filesystem permissions, but ensure `storage/` and `bootstrap/cache` are writable by the process running PHP (IIS/Valet/WSL/docker).

Running the app locally
-----------------------
Method A — PHP built-in server (quick dev):

```cmd
php artisan serve --host=127.0.0.1 --port=8000
```

Open: http://127.0.0.1:8000

Method B — Vite dev server (for front-end HMR):

1. Run Vite dev server in one terminal:

```cmd
npm run dev
```

2. Run the PHP server in another terminal:

```cmd
php artisan serve
```

Background jobs and queue
-------------------------
This project uses jobs and SMS-sending jobs (see `app/Jobs/SendBulkSmsToAllJob.php` and `app/Models/SmsCron.php`). For local testing you can use the `sync` queue driver, but for realistic behavior use `database` and run a worker:

```cmd
php artisan queue:work --tries=3
```

To start a single-run worker (process exits on completion):

```cmd
php artisan queue:listen
```

In production, run a process manager (Supervisor on Linux) or Windows Task Scheduler to keep workers alive.

Scheduler (cron)
-----------------
On Linux, add the Laravel schedule to cron (recommended):

*/1 * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

On Windows, use Task Scheduler to call `php artisan schedule:run` every minute. The scheduled tasks are defined in `app/Console/Kernel.php`.

SMS and logs
------------
- SMS settings are in `config/sms.php` and any environment variables that start with `SMS_`.
- There is a log `sms.log` at project root used by SMS helpers; check it when messages don't send.

Mail
----
Set mail driver settings in `.env` (MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS). Use Mailtrap or a similar dev SMTP provider for local testing.

Running tests
-------------
Run the test suite with:

```cmd
php artisan test
```

Or use PHPUnit directly:

```cmd
vendor\bin\phpunit
```

There is a `phpunit.xml` in the project root that configures test environment.

Common artisan commands
-----------------------
- `php artisan migrate:fresh --seed` — reset DB and seed
- `php artisan cache:clear` — clear Laravel cache
- `php artisan config:clear` — clear config cache
- `php artisan route:list` — list routes
- `php artisan optimize` — optimize framework (for production)

Project structure (high level)
------------------------------
- `app/` — core application code (Controllers, Models, Jobs, Console, Providers)
  - `app/Http/Controllers` — HTTP controllers
  - `app/Jobs` — queued jobs (SMS jobs here)
  - `app/Models` — Eloquent models (Customer, Sale, Purchase, SmsHistory, etc.)
- `config/` — configuration files
- `database/` — migrations, factories, seeders
- `resources/` — Blade views (`resources/views`) and frontend assets (`resources/js`, `resources/css`)
- `public/` — publicly served files and compiled assets
- `routes/` — `web.php`, `api.php` etc.

Developer notes / where to look
-------------------------------
- SMS logic: `app/Helpers/SMS.php`, `app/Helpers/CronSMS.php`, `app/Models/SmsSendBulk.php`, `app/Jobs/SendBulkSmsToAllJob.php`.
- Livewire components: `app/Http/Livewire` and `resources/views/livewire` (if used).
- Payment/invoice: check `app/Models/Payments.php`, `app/Models/Sale.php`.

Troubleshooting
---------------
- Composer memory errors: run `php -d memory_limit=-1 composer install`.
- 500 error: check `storage/logs/laravel.log` and `public/error_log`.
- Missing vendor autoload: run `composer install`.
- Blade compile errors: run `php artisan view:clear`.
- Jobs not dispatching: ensure `QUEUE_CONNECTION` is set and `php artisan queue:work` is running.

Deployment notes
----------------
- Use `php artisan config:cache`, `route:cache`, `view:cache` in production deployment scripts.
- Ensure `APP_KEY` is set and not regenerated in production.
- Database backups and migrations should be run as separate steps with a rollback plan.

Security and sensitive data
---------------------------
- Never commit `.env` to source control. Keep secrets in environment variables or a secure secret manager.
- Rotate any keys (SMS/Mail) if leaked.

How to contribute
-----------------
1. Create a feature branch from `develop` or `main` (follow repo branching policy).
2. Write tests for new features where applicable.
3. Run `composer test` / `php artisan test` locally.
4. Open a Pull Request with a clear description and testing steps.

Contact / maintainers
---------------------
If you need context not present in the repo, ask the original maintainers or check the project's README and any issue tracker used by the project.

Extras / Useful local commands
-----------------------------
- Clear config and cache quickly:

```cmd
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
```

- If DB migrations fail and you want a fresh start (DESTRUCTIVE):

```cmd
php artisan migrate:fresh --seed
```

Requirements coverage
---------------------
- Setup (composer/npm): Done — commands provided
- Local dev server: Done — `php artisan serve` and Vite instructions
- Database & migration: Done — migrate/seed commands provided
- Queue & Scheduler: Done — examples for queue worker and schedule
- Tests: Done — `php artisan test` and phpunit

Notes / assumptions
-------------------
If any of the assumptions (PHP/Node versions or database engine) are incorrect, update the `INSTRUCTIONS.md` or tell me what environment you plan to use and I will tailor the instructions.
