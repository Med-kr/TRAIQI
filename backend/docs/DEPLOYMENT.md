# Deployment Notes

## Environment

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain`
- `SESSION_SECURE_COOKIE=true`
- `QUEUE_CONNECTION=database`
- `CACHE_STORE=database`
- `MAIL_MAILER=smtp`
- `LOG_LEVEL=warning`

## First Setup

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
npm install
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Production Env Checklist

- Set a real `APP_URL` with `https://`.
- Set `SESSION_SECURE_COOKIE=true`.
- Use a real SMTP provider instead of `MAIL_MAILER=log`.
- Confirm production database credentials are restricted.
- Confirm queue tables exist and `QUEUE_CONNECTION=database`.

## HTTPS

- Terminate SSL at Apache, Nginx, or the reverse proxy.
- Keep `APP_URL` on `https://...`.
- The app now forces `https` URLs automatically in production.
- Verify login, password reset, and email verification links use `https`.

## Queue Workers

Run a persistent worker under a supervisor process:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Minimum monitoring:

- restart on failure
- alert when queued jobs accumulate
- review `failed_jobs` daily during pilot

## Email Flow

Before pilot:

- configure SMTP credentials
- send a real password reset email
- send a real email verification email
- confirm sender address and inbox delivery

## Backups

Minimum daily routine:

- dump the production database once per day
- copy `.env` and uploaded files if used
- keep backups outside the app server
- perform one restore test before pilot

Example MySQL backup command:

```bash
mysqldump -u your_user -p your_database > backup.sql
```

## Rollback

Keep one previous release ready. If deployment fails:

1. Put the app in maintenance mode.
2. Restore the previous release symlink or code snapshot.
3. Restore database backup if the failed release changed schema or data critically.
4. Restart queue workers.
5. Verify login, dashboard, notifications, and grading pages.

## Minimum Production Checklist

- Configure HTTPS at the reverse proxy or web server.
- Enable daily database backups and one restore test.
- Run queue workers with process supervision.
- Rotate logs and monitor disk usage.
- Verify mail delivery for password reset flow.
- Restrict production database credentials.
