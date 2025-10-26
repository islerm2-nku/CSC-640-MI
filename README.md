# CSC-640-MI

IRacing authorization url: https://members-login.iracing.com/?ref=https%3A%2F%2Fmembers-ng.iracing.com%2Fdata

Documentation for all service methods can be accessed at https://members-ng.iracing.com/data/doc
Each service has its own documentation page, like https://members-ng.iracing.com/data/doc/car
Each service method also has a documentation page such as https://members-ng.iracing.com/data/doc/car/assets

## Migrations

This project includes a small migration script at `db/create_db.php` that creates the database schema (idempotent). The repository runs Composer during image build so you don't need Composer on your host.

To build and start the app and database containers:

```bash
docker-compose up --build -d
```

Run the migration (wait a few seconds for MySQL to become healthy):

```bash
docker-compose run --rm migrate
```

You can also exec into the running `web` container and run the migration manually:

```bash
docker-compose exec web php /var/www/html/db/create_db.php
```

If you change migrations, re-run the migrate command. For production or more advanced workflows consider a proper migration tool (Phinx, Doctrine Migrations, or framework-provided tooling).

