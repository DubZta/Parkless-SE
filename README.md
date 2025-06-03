<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Parkless

Parkless is a Laravel-based web application that helps users find supermarkets that are free from illegal parking attendants (juru parkir liar). This app promotes safe, affordable, and comfortable city living aligned with **SDG 11** and **SDG 9**.

### Key Features

- Search for nearby supermarkets using **Leaflet.js + Geolocation**.
- Submit **reviews and reports** about parking experiences.
- Integrated **discussion forum** for community interaction.
- **Registered & Unregistered user** modes.

---

## Getting Started

Follow these steps to set up and run the application locally:

### Requirements

- PHP >= 8.0
- Composer
- Node.js & npm
- MySQL or compatible database
- Laravel Installer (optional)

### Installation

# 1. Clone the repository
git clone https://github.com/DubZta/Parkless-SE.git
cd Parkless-SE

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 4. Set up your database configuration in the .env file
# (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 5. Run migrations and seeders
php artisan migrate:fresh --seed

# 6. Serve the application
php artisan serve
