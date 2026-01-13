# Project Details

## Overview
This project is a multi-tenant SaaS platform, starting as a Modular Monolith for shared hosting, with a clear strategy for future evolution into a VPS/Microservices architecture. The goal is to provide managed backend APIs and extensible frontend starter kits, primarily targeting the Laravel developer ecosystem.

## Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+), Filament (for Admin/Tenant Panels), Laravel Sanctum (API Authentication), `spatie/laravel-multitenancy` (for multi-tenancy).
- **Frontend**: Nuxt.js 3 (Vue 3), Tailwind CSS (for styling).
- **Database**: MySQL 8+ (or PostgreSQL), single database for shared hosting, evolving to dedicated databases per tenant for VPS.
- **Caching/Queue**: Redis (recommended for VPS, potentially limited on shared hosting), third-party queue services (e.g., SQS) for shared hosting.
- **File Storage**: AWS S3 (or compatible S3 storage) for product files and generated starter kits.
- **Payment Gateways**: Stripe, PayPal.
- **Version Control Integration**: GitHub API for private repository distribution.

## Key Features (Planned)
- **Modular Monolith Architecture**: Products as internal Laravel packages.
- **Multi-Tenancy**: Single database with `spatie/laravel-multitenancy` for data isolation.
- **Evolvable Architecture**: Designed for seamless migration from monolith to microservices using interfaces and drivers.
- **Filament Dashboards**: For Super Admins (platform management) and Tenants (app/service management).
- **Nuxt.js Frontend**: Public-facing site and potentially a customer-facing dashboard (though Filament is preferred for tenant management).
- **API-First Approach**: All backend functionality exposed via APIs.
- **Downloadable Starter Kits**: Pre-configured Nuxt.js frontends with injected credentials for easy deployment by users.
- **Frontend Plugin Ecosystem**: A system for users to install plugins into their downloaded starter kits (via backend modification and re-download).
- **Private GitHub Repository Distribution**: Sell and distribute projects directly from private GitHub repositories.
- **Subscription & Billing**: Managed through the platform.
- **API Key Management**: For tenant applications.

## Development Status
- Comprehensive architectural plans are finalized for both shared hosting and future VPS evolution.
- Strategic analysis and feature plans are complete.

## Conventions & Standards
- Adherence to Laravel Boost Guidelines (PHP 8.3.22, Laravel v12, Pest v4, Tailwind CSS v4).
- PHP/Laravel: 4 spaces indentation, PSR-4, camelCase for methods/variables, PascalCase for classes, typed properties/parameters/return types, PHPDoc for public methods, try/catch for errors.
- Vue/Nuxt/TypeScript: `<script setup lang="ts">`, Composition API, PascalCase for components, camelCase for variables/methods, TypeScript interfaces, Tailwind CSS.
- General: LF line endings, final newlines, trimmed trailing whitespace, minimal descriptive comments, security best practices.

## Testing
- Backend: Pest (v4) for unit and feature tests. `php artisan test` to run all tests.
- Frontend: (To be determined, likely Vitest or similar for Nuxt/Vue)

## Build/Dev Commands
- Backend: `composer test`, `vendor/bin/pint`, `php artisan serve`, `npm run build`
- Frontend: `npm run dev`, `npm run build`, `npm run generate`
- Full-stack: `composer run setup`, `composer run dev`