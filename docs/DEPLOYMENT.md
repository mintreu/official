# Mintreu Platform - Deployment Guide

## Overview

Mintreu is a full-stack platform built with Laravel 12 (backend API) and Nuxt 4 (frontend). This guide covers deployment for both development and production environments.

---

## Architecture

- **Backend**: Laravel 12 REST API with Laravel Sanctum authentication
- **Frontend**: Nuxt 4 SSR/SPA with TypeScript
- **Database**: MySQL 8.0+
- **Authentication**: Token-based via Laravel Sanctum

---

## Prerequisites

### Backend Requirements
- PHP 8.3+
- Composer 2.x
- MySQL 8.0+
- Node.js 18+ (for Vite assets)

### Frontend Requirements
- Node.js 18+
- npm or pnpm

---

## Backend Deployment

### 1. Clone and Install

```bash
cd backend
composer install --optimize-autoloader --no-dev
```

### 2. Environment Configuration

```bash
cp .env.example .env
```

Configure your `.env`:

```env
APP_NAME="Mintreu"
APP_ENV=production
APP_KEY=  # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://api.your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Sanctum (IMPORTANT for API authentication)
SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.com
SPA_URL=https://your-frontend-domain.com

# Frontend URL for CORS
FRONTEND_URL=https://your-frontend-domain.com
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 5. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Web Server Configuration

#### Apache (.htaccess already included)

Point your document root to `backend/public`

#### Nginx

```nginx
server {
    listen 80;
    server_name api.your-domain.com;
    root /path/to/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Frontend Deployment

### 1. Install Dependencies

```bash
cd frontend
npm install
```

### 2. Environment Configuration

Create `.env`:

```env
NUXT_PUBLIC_API_BASE=https://api.your-domain.com
```

### 3. Build for Production

```bash
npm run build
```

This creates a `.output` directory with the production build.

### 4. Deployment Options

#### Option A: Node.js Server

```bash
node .output/server/index.mjs
```

#### Option B: Static Hosting (Netlify, Vercel, Cloudflare Pages)

The `.output/public` directory contains static assets that can be deployed to any static hosting provider.

#### Option C: PM2 (Recommended for VPS)

```bash
npm install -g pm2
pm2 start npm --name "mintreu-frontend" -- start
pm2 save
pm2 startup
```

### 5. Nginx Configuration for Frontend

```nginx
server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

## API Endpoints

### Public Endpoints (No Authentication Required)

- `GET /api/projects` - List all projects
- `GET /api/projects/{slug}` - Get single project
- `GET /api/case-studies` - List all case studies
- `GET /api/case-studies/{slug}` - Get single case study
- `GET /api/marketplace` - List all products
- `GET /api/marketplace/{slug}` - Get single product
- `GET /api/articles` - List all articles
- `GET /api/articles/{slug}` - Get single article
- `GET /api/home` - Get homepage data

### Authentication Endpoints

- `POST /api/register` - Register new user
- `POST /api/login` - Login (returns auth token)
- `POST /api/logout` - Logout (requires auth)
- `GET /api/user` - Get authenticated user (requires auth)

### Query Parameters

All list endpoints support:
- `page` - Page number for pagination
- `per_page` - Items per page (default: 15, max: 100)
- `featured` - Filter by featured items (true/false)
- `search` - Search by title/description
- `category` - Filter by category

Example: `/api/projects?featured=true&per_page=6`

---

## Database Schema

### Projects Table
- `id` - Primary key
- `slug` - Unique URL-friendly identifier
- `title` - Project title
- `description` - Short description
- `content` - Full HTML content
- `image` - Project image URL
- `category` - Project category
- `technologies` - JSON array of tech stack
- `status` - Status (Draft/Published/archived)
- `featured` - Boolean for homepage display
- `live_url` - Optional live demo URL
- `github_url` - Optional GitHub repository URL

### Case Studies Table
- Similar to projects with additional fields:
- `client` - Client name
- `industry` - Industry sector
- `duration` - Project duration
- `challenge` - Problem statement
- `solution` - Solution description
- `results` - JSON array of results/metrics

### Products Table
- `id`, `slug`, `title`, `description`, `content`
- `image` - Product image
- `price` - Decimal price
- `category` - Product category (plugin/theme/api/freebie)
- `type` - Product type (free/premium/freemium)
- `download_url`, `demo_url`, `github_url`, `documentation_url`
- `version` - Current version
- `downloads` - Download count
- `rating` - Average rating (0-5)
- `status`, `featured`

### Articles Table
- `id`, `slug`, `title`, `excerpt`, `content`
- `image` - Article featured image
- `category` - Article category
- `tags` - JSON array of tags
- `author` - Author name
- `reading_time` - Minutes to read
- `status`, `featured`
- `published_at` - Publication timestamp

---

## Security Checklist

### Backend
- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secured
- [ ] CORS configured for frontend domain only
- [ ] Sanctum stateful domains set correctly
- [ ] Rate limiting enabled on API routes
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] Storage and cache directories writable by web server

### Frontend
- [ ] API base URL points to production backend
- [ ] No sensitive data in environment variables
- [ ] SSL/TLS enabled (HTTPS)

---

## Monitoring & Maintenance

### Laravel Logs
Check logs at: `backend/storage/logs/laravel.log`

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Backups
Set up automated MySQL backups:
```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### Queue Workers (if using queues)
```bash
php artisan queue:work --daemon
```

---

## Troubleshooting

### Issue: CORS Errors
**Solution**: Check `config/cors.php` and ensure `FRONTEND_URL` is set in `.env`

### Issue: 419 CSRF Token Mismatch
**Solution**: Verify Sanctum configuration and `SANCTUM_STATEFUL_DOMAINS` in `.env`

### Issue: 500 Internal Server Error
**Solution**:
1. Check `storage/logs/laravel.log`
2. Ensure proper file permissions
3. Run `php artisan config:clear`

### Issue: API Returns Empty Data
**Solution**:
1. Check database connection
2. Run migrations: `php artisan migrate`
3. Seed database: `php artisan db:seed`

### Issue: Frontend Can't Connect to API
**Solution**:
1. Verify `NUXT_PUBLIC_API_BASE` in frontend `.env`
2. Check backend CORS settings
3. Ensure backend is accessible from frontend domain

---

## Development Environment

### Backend
```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8000
```

### Frontend
```bash
cd frontend
npm run dev
```

Frontend will be available at: `http://localhost:3000` or `http://localhost:3001`
Backend API will be available at: `http://localhost:8000`

---

## Useful Commands

### Backend
```bash
# Fresh database with seed data
php artisan migrate:fresh --seed

# Create new API controller
php artisan make:controller Api/YourController --api

# Create model with migration and factory
php artisan make:model YourModel -mf

# Run tests
php artisan test
```

### Frontend
```bash
# Development with hot reload
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Type check
npm run typecheck
```

---

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs/12.x
- Nuxt Documentation: https://nuxt.com/docs
- Laravel Sanctum: https://laravel.com/docs/12.x/sanctum

---

**Note**: This platform is production-ready with real database data. All API endpoints are tested and working correctly.
