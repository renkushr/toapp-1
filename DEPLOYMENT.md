# To-app Production Deployment

This project is a Laravel 11 application with a root `index.php`, root `assets/`, and the Laravel core in `core/`.

## Recommended first production path

Use a small Ubuntu VPS with Docker Compose, then put HTTPS in front with Cloudflare or a reverse proxy such as Caddy/Nginx. This keeps the current project structure intact and avoids shared-hosting permission issues.

## Files

- `Dockerfile.prod` builds a production PHP 8.3 Apache image.
- `docker-compose.prod.yml` runs the app and MariaDB.
- `.env.production.example` is the template for production secrets.
- `deploy/entrypoint.prod.sh` warms Laravel caches at container start.
- `deploy/php-production.ini` disables display errors and enables OPcache.
- `install/toapp_fresh.sql` initializes a clean production database without old users, transactions, tickets, deposits, withdrawals, or logs.

## Server checklist

1. Point your domain DNS `A` record to the VPS IP.
2. Install Docker and Docker Compose on the VPS.
3. Upload or pull this project into `/opt/toapp` or another server folder.
4. Copy the env template:

```bash
cp .env.production.example .env.production
```

5. Edit `.env.production`:

```bash
APP_URL=https://your-real-domain.com
APP_KEY=base64:...
DB_PASSWORD=strong-random-password
DB_ROOT_PASSWORD=another-strong-random-password
MAIL_* real SMTP settings
```

6. Generate an app key locally or on the server:

```bash
docker compose -f docker-compose.prod.yml run --rm app php /var/www/html/core/artisan key:generate --show
```

7. Build and start:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build
```

8. Check logs:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml logs -f app
```

## After first start

Run these after changing environment values or code:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php /var/www/html/core/artisan optimize:clear
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php /var/www/html/core/artisan config:cache
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php /var/www/html/core/artisan view:cache
```

Do not enable `route:cache` yet. The inherited route files currently contain a duplicate route name (`admin.login`), so `route:cache` fails until those admin routes are cleaned up.

## HTTPS

Do not run real user traffic over plain HTTP. Add HTTPS before launch. The simplest option is Cloudflare proxy with "Full" or "Full strict" SSL, or a local reverse proxy such as Caddy/Nginx in front of the app container.

## Cron

This application has a public `/cron` endpoint that processes scheduled jobs such as investment returns. Add a server cron after HTTPS is live:

```cron
* * * * * curl -fsS https://your-real-domain.com/cron >/dev/null 2>&1
```

## Agent sample data

After migration, seed demo agents and auto-assign existing members by ZIP:

```bash
docker compose exec app php /var/www/html/core/artisan toapp:seed-agents
```

Demo logins at `/agent`: `agent1` / `Agent@12345` (license `AG-DEMO-001`, ZIPs 90210–90212), plus `agent2` and `agent3`.

Seed sample insurance products for Check Insure:

```bash
docker compose exec app php /var/www/html/core/artisan toapp:seed-products
```

## Agent chat (Pusher)

Set in production env:

```text
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=...
PUSHER_APP_KEY=...
PUSHER_APP_SECRET=...
PUSHER_APP_CLUSTER=mt1
```

Without Pusher, agent chat falls back to 5-second polling automatically.

If your hosting panel has a cron UI, use the same command there. After launch, check the admin cron page and confirm `last_cron` updates.

## Fresh database

The production compose file imports `install/toapp_fresh.sql` only when the database volume is empty. This is the correct path for a brand-new launch.

Temporary admin account after a fresh install:

```text
URL: https://your-real-domain.com/admin
Username: admin
Password: Adm-0b265256c18d!
```

Change this password immediately after first login.

If you need to reset a test deployment and destroy all production data, stop the stack and remove the project volumes:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml down -v
docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build
```

Only use `down -v` before real users exist, because it deletes the database volume.

## Important production settings

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-real-domain.com`
- Replace all database passwords.
- Configure real SMTP. Do not leave `MAIL_MAILER=log`.
- Configure payment gateway keys only after confirming the business flow.
- Back up `db_data` regularly.
