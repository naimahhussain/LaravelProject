# Naimah Electronics Store

**Student Name:** Naimah Naimah  
**Student Number:** 20232022182

---

## About the Project

Naimah Electronics Store is a full-stack e-commerce web application built with **Laravel 12** and **PHP 8.4**. It allows customers to browse electronics products by category, add items to a shopping cart, and place orders. A secure admin panel lets staff manage products, categories, and orders in real time.

---

## Features

### Customer Side
- Browse all products on the homepage
- **Filter by category** (Phones, Laptops, TVs, Tablets, Cameras, Audio, Gaming, Accessories)
- Add products to cart with instant feedback (no page reload)
- View and manage cart — update quantities, remove items
- Checkout with name, email, phone, and delivery address
- User registration and login

### Admin Panel (`/admin`)
- Protected — only admin accounts can access it
- **Dashboard** with live stats (products, categories, orders, revenue) — auto-refreshes every 15 seconds
- New orders placed by customers appear on the dashboard automatically
- Full **CRUD** for Products and Categories
- **Orders management** — view order details and update status (Pending → Processing → Shipped → Delivered → Cancelled)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 (PHP 8.4) |
| Database | SQLite |
| Frontend | Bootstrap 4, Font Awesome 6, jQuery |
| Admin UI | SB Admin 2 (via CDN) |
| Auth | Laravel session-based authentication |

---

## Requirements

- PHP 8.4+
- Composer 2+
- Git

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone <repository-url>
cd LaravelProject
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
cp .env.example .env
```

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Run database migrations and seed sample data

```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- 8 product categories
- 18 sample electronics products
- 4 sample orders
- An admin user account

### 6. Start the development server

```bash
php artisan serve
```

The app will be available at **http://localhost:8000**

---

## Default Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | admin123 |
| Test User | test@example.com | password |

> The admin account gives access to the `/admin` dashboard.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php        # Login, register, logout
│   │   ├── CartController.php        # Cart & checkout
│   │   ├── FrontController.php       # Homepage
│   │   └── Admin/
│   │       ├── AdminController.php   # Dashboard
│   │       ├── ProductController.php # Product CRUD
│   │       ├── CategoryController.php# Category CRUD
│   │       └── OrderController.php   # Order management
│   └── Middleware/
│       └── AdminMiddleware.php       # Admin route protection
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── User.php
database/
├── migrations/                       # All table definitions
└── seeders/
    └── DatabaseSeeder.php            # Sample data
resources/views/
├── home.blade.php                    # Storefront
├── cart.blade.php                    # Cart & checkout
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
└── admin/
    ├── adminbase.blade.php           # Admin layout
    ├── index.blade.php               # Dashboard
    ├── products/                     # Product CRUD views
    ├── categories/                   # Category CRUD views
    └── orders/                       # Order views
routes/
└── web.php                           # All application routes
```

---

## Key Routes

| Method | URL | Description |
|---|---|---|
| GET | `/` | Homepage / product listing |
| GET | `/login` | Login page |
| GET | `/register` | Registration page |
| GET | `/cart` | Shopping cart |
| POST | `/cart/add/{product}` | Add product to cart (AJAX) |
| POST | `/checkout` | Place an order |
| GET | `/admin` | Admin dashboard |
| GET | `/admin/products` | Manage products |
| GET | `/admin/categories` | Manage categories |
| GET | `/admin/orders` | Manage orders |

