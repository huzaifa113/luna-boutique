<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Luna Boutique">
</p>

<h1 align="center">Luna Boutique — E-Commerce & POS System</h1>

<p align="center">
  A full-featured e-commerce platform with an integrated Point of Sale (POS) terminal, inventory management, and a powerful Filament admin panel — built with Laravel 13.
</p>

<p align="center">
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php" alt="PHP"></a>
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel" alt="Laravel"></a>
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/Filament-5-EA580B?style=flat-square" alt="Filament"></a>
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square" alt="Livewire"></a>
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=flat-square&logo=tailwindcss" alt="Tailwind CSS"></a>
  <a href="https://github.com/huzaifa113/luna-boutique"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="License"></a>
</p>

---

## ✨ Features

### 🛍️ Customer Storefront
- **Product Catalog** — Browse products by category and brand with search functionality
- **Product Details** — Multiple product images, reviews, and ratings
- **Shopping Cart** — Add, update, and remove items with quantity management
- **Checkout** — Address management, coupon discounts, and order placement
- **Order Tracking** — Customers can view their order history and status
- **Wishlist** — Save favorite products for later
- **Product Reviews** — Customers can rate and review purchased products
- **Returns & Exchanges** — Submit return/exchange requests with attachments
- **Contact & Newsletter** — Contact form and newsletter subscription

### 🏪 Point of Sale (POS)
- **POS Terminal** — Fast, keyboard-friendly sales interface for in-store transactions
- **Customer Management** — Create and manage walk-in customers
- **Sales & Payments** — Record sales, track customer payments, and manage balances
- **Invoice Generation** — Formatted invoices with configurable company details, currency (PKR), and terms

### 📦 Inventory & Stock Management
- **Products & Variants** — Products with multiple units (e.g., piece, dozen, box)
- **Unit Conversion** — Automatic conversion between product units
- **Stock Movements** — Track every stock in/out movement with reasons
- **Low Stock Alerts** — Monitor inventory levels with reorder thresholds
- **Purchases** — Record purchases from vendors with cost prices
- **Vendor Management** — Track vendors and vendor payments

### 🎛️ Filament Admin Panel
- **Dashboard** — Store stats overview, revenue chart, orders-by-status chart, top products, and latest orders
- **Full CRUD Resources** — Products, Categories, Brands, Orders, Customers, Vendors, Purchases, Sales, Coupons, Reviews, Returns/Exchanges, Stock Movements, Users, and more
- **Inventory Management Page** — Dedicated inventory view with stock controls
- **POS Terminal Page** — In-panel POS access for staff

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | [Laravel 13](https://laravel.com) (PHP 8.3+) |
| **Admin Panel** | [Filament 5](https://filamentphp.com) |
| **Frontend** | [Livewire 4](https://livewire.laravel.com), [Alpine.js 3](https://alpinejs.dev), [Tailwind CSS 3](https://tailwindcss.com), [Bootstrap 5](https://getbootstrap.com) |
| **Build Tool** | [Vite](https://vitejs.dev) |
| **Database** | SQLite (default), MySQL, PostgreSQL, or SQL Server |
| **Testing** | [Pest](https://pestphp.com) / PHPUnit |
| **Code Style** | [Laravel Pint](https://laravel.com/docs/pint) |

---

## 📋 Requirements

- **PHP** >= 8.3
- **Composer** 2.x
- **Node.js** >= 20
- **NPM** or **Yarn**
- A database (SQLite, MySQL, PostgreSQL, or SQL Server)

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/huzaifa113/luna-boutique.git
cd luna-boutique
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database connection in `.env`. The default is SQLite:

```bash
touch database/database.sqlite
```

### 4. Run migrations & seeders

```bash
php artisan migrate --seed
```

This seeds the database with default data including units, demo products, and a demo POS dataset.

### 5. Build frontend assets

```bash
npm run build
```

### 6. Start the application

```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

---

## ⚡ Quick Setup (One Command)

```bash
composer run setup
```

This runs the full setup: installs dependencies, creates `.env`, generates the app key, runs migrations, installs npm packages, and builds assets.

---

## 🧑‍💻 Development

Run the dev server, queue worker, and Vite hot-reload together:

```bash
composer run dev
```

Or run them individually:

```bash
php artisan serve          # Web server
php artisan queue:listen   # Queue worker
npm run dev                # Vite dev server (hot reload)
```

---

## 🧪 Testing

```bash
composer test
```

Or run Pest directly:

```bash
php artisan test --compact
```

Run a specific test:

```bash
php artisan test --compact --filter=testName
```

---

## 🎨 Code Style

This project uses [Laravel Pint](https://laravel.com/docs/pint) for code formatting:

```bash
vendor/bin/pint --dirty
```

---

## 🗂️ Project Structure

```
app/
├── Filament/
│   ├── Pages/          # Dashboard, ManageInventory, PosTerminal
│   ├── Resources/      # Admin CRUD resources (Products, Orders, Customers, etc.)
│   └── Widgets/        # Charts & stats widgets
├── Http/
│   ├── Controllers/    # Storefront & POS controllers
│   └── Requests/       # Form request validation
├── Livewire/           # Livewire components (POS Terminal, Inventory Table)
├── Models/             # Eloquent models
├── Providers/          # App & Filament panel providers
├── Services/           # Business logic (Checkout, Inventory, Sales, Stock, etc.)
└── Support/            # Support classes (DashboardPeriod)
database/
├── factories/          # Model factories
├── migrations/         # Database schema
└── seeders/            # Database seeders
routes/
├── web.php             # Web routes
├── auth.php            # Authentication routes
└── console.php         # Console routes
```

---

## 🧩 Key Services

| Service | Purpose |
|---------|---------|
| `CheckoutService` | Handles the checkout flow, order creation, and coupon application |
| `InventoryService` | Manages inventory levels and stock adjustments |
| `SaleService` | Processes POS sales and sale items |
| `PurchaseService` | Handles vendor purchases and purchase items |
| `StockService` | Records stock movements and updates quantities |
| `UnitConversionService` | Converts quantities between product units |
| `InvoiceFormatterService` | Formats POS invoices with company details and currency |

---

## 🛠️ Admin Panel

Access the Filament admin panel at:

```
http://localhost:8000/admin
```

Create an admin user:

```bash
php artisan make:filament-user
```

### Admin Resources

- Products & Product Images
- Categories & Brands
- Orders & Order Items
- Customers & Customer Payments
- Vendors & Vendor Payments
- Purchases
- Sales
- Stock Movements
- Coupons
- Reviews
- Returns & Exchanges
- Contact Submissions
- Newsletter Subscriptions
- Users
- Addresses

---

## ⚙️ POS Configuration

The POS module can be configured via environment variables in `.env`:

```env
POS_COMPANY_NAME="Luna Boutique"
POS_COMPANY_ADDRESS=""
POS_COMPANY_PHONE=""
POS_COMPANY_EMAIL=""
POS_COMPANY_TAX_NUMBER=""
POS_COMPANY_LOGO=""
POS_CURRENCY_SYMBOL=Rs
POS_CURRENCY_CODE=PKR
POS_CURRENCY_WORDS=Rupees
POS_CURRENCY_SUBUNIT_WORDS=Paisa
POS_INVOICE_TERMS="Goods once sold are not returnable without prior approval."
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
