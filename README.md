<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## XDR / Wazuh console

The authenticated admin area includes an XDR overview at `/admin/xdr` and a request activity table at `/admin/xdr/requests`. After deploying, run `php artisan migrate` (or `php artisan migrate --force` in production) to create the request activity table. Until the migration has run, request logging is skipped and the XDR pages display a setup notice; normal site requests continue. The web middleware records the client IP, method, path, response status, and timestamp; query strings, request bodies, and user agents are not stored.

### Existing databases without migration history

If importing this project into a database that already has some of its tables but no matching `migrations` records, do not run `migrate:fresh`, `migrate:refresh`, or `db:wipe`. First back up the database, then run `php artisan migrate:baseline` to preview which project migrations have all their expected tables already present. Check that the existing table schemas match the corresponding migration definitions. If a migration is only partially present, the command stops without changing migration history; reconcile that schema manually before continuing.

After confirming the preview, run `php artisan migrate:baseline --apply`. This only creates/updates Laravel's migration-history table and records migrations whose complete table sets already exist; it does not create, drop, or modify application tables. Then run `php artisan migrate` (or `php artisan migrate --force` in production) to create tables for migrations that had none of their expected tables, including `request_activities` when it is absent. On a new, empty database, skip the baseline step and run `php artisan migrate` directly.

The `/admin/xdr/benefits` page presents manually curated XDR and SIEM reference benefits, including telemetry visibility, correlation, response, investigation, compliance, search, automation, governance, resilience, and cost control, plus a comparison and shared business value. The page reads these records through the `SecurityBenefitModule`, `SecurityBenefitCategory`, and `SecurityBenefit` models; the `SecurityBenefitsSeeder` inserts the initial catalog without replacing or deleting existing records. After applying the additive schema migration, run `php artisan db:seed --class="Database\\Seeders\\SecurityBenefitsSeeder"` (or use `php artisan db:seed` for the standard database seeder). Access remains behind admin authentication and the benefit policy. These are potential outcomes only, not claims of enabled platform features or measured results. This project does not connect to a live Wazuh, XDR, or SIEM service through the benefits page.

The Wazuh alert feed and agent inventory are explicitly demo telemetry, not a live Wazuh connection. Configure trusted proxies before deployment so client IPs are only accepted from known proxies, and apply an appropriate retention period and privacy notice for stored IP addresses.

### Admin audit log

The authenticated audit log is available at `/admin/xdr/audit-log`. Run `php artisan migrate` to create the additive `audit_logs` table (or use the documented baseline workflow first when reconciling an existing database). The log records successful/failed sign-ins, sign-outs, and admin profile/content creates, updates, and deletes. If the table is not present, audit writes are skipped and the page displays migration instructions without interrupting normal site actions.

Audit records contain administrator IDs, source IP addresses, request paths, and selected before/after values. Credentials, tokens, and uploaded image contents/references are excluded from value snapshots. Restrict access to the database and audit page, inform users as required by your privacy policy, and set a retention/deletion schedule appropriate to your legal and operational requirements.

### Admin password update
userid: esaju.cse@gmail.com  password: Profile2026#
Authenticated administrators can change their password from the **Update Password** card on `/admin`. The form requires the current password and a matching new password of at least 12 characters with uppercase, lowercase, numeric, and symbol characters. On success, the new password is hashed, the remember token is rotated, and the active session ID is regenerated. No email reset flow or extra database migration is used.

### Approximate IP geolocation

Request activity and audit records can include an approximate country, region/city, and coordinates. Geolocation is opt-in: set `IP_GEOLOCATION_ENABLED=true` in `.env` and run `php artisan config:cache` if configuration is cached. The default provider is IPwho.is at `https://ipwho.is/{ip}` and requires no API key; set `IP_GEOLOCATION_URL` to a compatible provider endpoint if needed. Adjust `IP_GEOLOCATION_TIMEOUT`, `IP_GEOLOCATION_CACHE_DAYS`, and `IP_GEOLOCATION_REQUESTS_PER_MINUTE` to fit the environment. Apply `php artisan migrate` to add nullable location fields to existing activity and audit tables; the migration preserves existing records.

Lookups run only on the server. Private, loopback, and reserved IPs are skipped; successful results are cached (30 days by default), failed results briefly (60 seconds), and provider calls are rate-limited (30 per minute by default). Public visitor locations appear only when the site is accessed publicly, geolocation is enabled, and trusted proxies are configured correctly. Approximate coordinates are displayed with a Google Maps link; opening that link shares the coordinates—not the original IP—with Google. IP geolocation can reveal personal data: disclose the provider transfer, limit retention/access, and enable the integration only after reviewing the provider and your privacy requirements.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
