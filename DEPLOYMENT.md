# EventPulse — Deployment

## Stack
- Laravel 12 / PHP 8.3
- Apache in Docker
- PostgreSQL (recommended: Neon free plan)
- Render Free Web Service

## Before deploying
1. Push the contents of this folder to the root of a GitHub repository.
2. Create a PostgreSQL database on Neon and copy its connection string. Keep the password private.
3. Generate an APP_KEY locally with:
   `php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"`
4. In Render, create a Blueprint from the GitHub repository (or create a Docker Web Service).
5. Set `APP_KEY`, `APP_URL`, and `DB_URL` in the Render service environment:
   - `APP_KEY`: generated key above
   - `APP_URL`: your Render URL, e.g. `https://eventpulse.onrender.com`
   - `DB_URL`: PostgreSQL connection URL from Neon, including `sslmode=require`
6. Deploy. Container startup runs migrations and seeds demo data once. The seeder now exits if any user already exists, so a restart does not duplicate demo accounts.

## Notes
- Do not commit `.env` or database credentials.
- Render Free services sleep after 15 minutes idle and wake in about a minute.
- Neon free database has usage/storage limits; review its current plan limits.
- This setup is intended for a classroom demo, not production.
- Seeded demo accounts use password `password`. Change/remove them before using the app with real users.
