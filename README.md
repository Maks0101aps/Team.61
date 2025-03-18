PROJECT school61project


## Installation

Clone the repo locally:

```sh
git clone (https://github.com/Maks0101aps/school61project.git) 
cd school61project
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run build
```

Setup configuration:

```sh
copy .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```
Build assets:
```sh
npm run dev
```

Run the dev server (the output will give the address):

```sh
php -S 127.0.0.1:8000 -t public
```

You're ready to go! Visit Ping CRM in your browser, and login with:

- **Username:** johndoe@example.com
- **Password:** secret

