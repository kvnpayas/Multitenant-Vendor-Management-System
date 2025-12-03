
# Multi‑Tenant Vendor Management System

This project is a Laravel + Vue 3 (Inertia, Pinia) single‑page application designed for vendor and invoice management with tenant isolation and role‑based access.


## Features

- Multi‑tenant architecture with role‑based UI (Admin, Accountant).
- User, Vendor, and Invoice CRUD pages.
- Laravel backend (API, Sanctum authentication).
- Vue 3 frontend with Pinia state management.
- Role‑based routing.


## Prerequisites

- PHP 8.2+
- Composer
- Node.js 20+
- npm
- MySQL
## Installation

1. Clone repository

```bash
  git clone https://github.com/kvnpayas/Multitenant-Vendor-Management-System.git
  cd Multitenant-Vendor-Management-System
```

2. Environment setup

```bash
  cp .env.example .env
```
Update .env with your database credentials:

```bash
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=
  DB_DATABASE=mvms
  DB_USERNAME=root
  DB_PASSWORD=
```

3. Install dependencies

```bash
  composer install
  npm install
```

4. Generate key

```bash
  php artisan key:generate
```

5. Run migrations and seed demo data

```bash
  php artisan migrate --seed
```

6. Start development servers

Run Laravel backend with Vite frontend:

```bash
  php artisan serve
  npm run dev
```


    
## Accessing the system

- App → http://localhost:8000 (Laravel serves Vue via Inertia)
## Default Accounts

**Admin:** admin@example.com / password12

**Accountant:** accountan@example.com / password12
