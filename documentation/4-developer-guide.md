# Developer Guide

This guide helps new developers get the project running quickly and understand the main development workflow for ZioraCMS.

## 1. Requirements

Make sure the following are installed locally:

- PHP 8.5+
- Composer
- Node.js and npm
- A database such as MySQL or PostgreSQL
- Git

## 2. Local setup

```bash
git clone <repository-url>
cd zioracms
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with your local database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zioracms
DB_USERNAME=root
DB_PASSWORD=your_password
```

Run the database setup:

```bash
php artisan migrate
php artisan storage:link
php artisan builder:init
```

The `builder:init` command seeds roles, permissions, and prompts you to create an admin user.

## 3. Start the app

Run the development stack:

```bash
composer dev
```

This starts the Laravel app and the frontend assets. Open the app in your browser at:

```text
http://127.0.0.1:8000
```

Admin login is available at:

```text
http://127.0.0.1:8000/cp/login
```

## 4. Project structure

- `app/` — core Laravel application code
- `bootstrap/` — application bootstrap files
- `Modules/` — feature modules such as Auth, Page, Layout, Media, Menu, and Dashboard
- `resources/js/` — Vue/Inertia frontend pages and components
- `resources/views/` — module-specific views
- `database/migrations/` — database schema changes
- `routes/` — web and console routes
- `tests/` — feature and unit tests

## 5. Common commands

### Run tests

```bash
php artisan test
```

### Build frontend assets

```bash
npm run build
```

### Refresh routes and configuration

```bash
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

## 6. Development workflow

- Create or update features inside the relevant module under `Modules/`
- Use existing controllers, actions, and DTOs before introducing new patterns
- Keep frontend changes in the relevant Vue page or component under `resources/js/` or the module resource folder
- Run tests before finishing changes

## 7. Troubleshooting

### Vite manifest error

If you see a Vite manifest error, run:

```bash
npm run build
```

### Permission issues in admin

If dashboard actions return 403 errors, make sure the current user has the correct role and permissions, usually through the seeded admin role created by `builder:init`.

## 8. Recommended next steps

- Review the existing modules in `Modules/`
- Explore the admin area at `/cp`
- Start with a small feature or bug fix and follow the existing module patterns
