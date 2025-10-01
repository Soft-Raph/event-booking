# Event Booking API (Laravel 11 + Sanctum)

A clean, standards-driven implementation for an Event Booking backend.

## Stack
- Laravel 11, PHP 8.2+
- Sanctum for API auth
- Queues (database) for notifications

## Setup
```bash
composer install
cp .env.example .env
php artisan key:generate

# Sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate

# Queues
php artisan queue:table && php artisan migrate
php artisan queue:work

# Documentation
https://documenter.getpostman.com/view/15150940/2sB3QFRsS2#f538ccca-91cd-4bf0-87b7-5cf9fe99330c
