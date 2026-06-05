# LitleMart - Multi Vendor Marketplace Installation Guide

LitleMart is a high-performance multi-vendor marketplace built with PHP Native 8.2+, following clean MVC architecture and modern UI standards.

## Prerequisites
- **XAMPP** (with PHP 8.2 or higher)
- **Composer**
- **Apache & MySQL** (included in XAMPP)

## Installation Steps

### 1. Clone or Extract Project
Place the project folder `LitleMart` in your XAMPP `htdocs` directory:
`C:\xampp\htdocs\LitleMart`

### 2. Configure Virtual Host (Optional but Recommended)
To run the project at `http://litlemart.local`, add this to your `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/LitleMart/public"
    ServerName litlemart.local
    <Directory "C:/xampp/htdocs/LitleMart/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
*Note: If you don't use a vhost, the app will be accessible at `http://localhost/LitleMart/public`.*

### 3. Database Setup
1. Open PHPMyAdmin (`http://localhost/phpmyadmin`).
2. Create a new database named `litlemart`.
3. Import the `database/migration.sql` file into the `litlemart` database.

### 4. Environment Configuration
1. Open the `.env` file in the project root.
2. Update the following values:
   - `DB_DATABASE=litlemart`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=` (leave blank if no password set in XAMPP)
   - `MIDTRANS_SERVER_KEY=` (get from Midtrans Dashboard)
   - `RAJAONGKIR_API_KEY=` (get from RajaOngkir Dashboard)

### 5. Install Dependencies
Run the following command in the project root directory:
```bash
composer install
```

### 6. File Permissions
Ensure the `storage` and `public/uploads` directories are writable by the server.

## Features Summary
- **Multi-vendor**: Vendors can manage their own stores and products.
- **RBAC**: Role-based access control (Admin, Vendor, Customer, Staff).
- **Payment Gateway**: Integrated with Midtrans Snap.
- **Shipping**: Integrated with RajaOngkir.
- **Modern UI**: Built with TailwindCSS and AlpineJS for a premium experience.

## Default Credentials (if seeded)
- **Admin**: admin@litlemart.com / password123
- **Test User**: user@example.com / password123
