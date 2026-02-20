# Laragon + Hostinger Structure (Mintreu Multi-Project)

Date: 2026-02-19  
Goal: run all projects locally with subdomains, and mirror clean production layout for `mintreu.com`.

## 1) Recommended Folder Layout

Base path:
`C:\laragon\www\mintreu\server`

```text
C:\laragon\www\mintreu\server
├── official
│   ├── apiserver
│   └── client
├── shopcore
│   ├── apiserver
│   └── client
├── helpdesk
│   ├── apiserver
│   └── client
├── licenseops
│   ├── apiserver
│   └── client
├── public_html
│   ├── local-map
│   │   ├── api.mintreu.test        -> official\apiserver\public
│   │   ├── shopcore-api.mintreu.test -> shopcore\apiserver\public
│   │   ├── helpdesk-api.mintreu.test -> helpdesk\apiserver\public
│   │   └── licenseops-api.mintreu.test -> licenseops\apiserver\public
│   └── production-map
│       ├── mintreu.com
│       ├── api.mintreu.com
│       ├── shopcore-api.mintreu.com
│       ├── helpdesk-api.mintreu.com
│       └── licenseops-api.mintreu.com
└── plans
```

Notes:
1. Keep code in project folders (`official`, `shopcore`, etc.).
2. `public_html` holds deployment mapping/docs/staging artifacts, not source of truth.
3. Each API project keeps its own seeder pipeline.

## 2) Local Domains (Laragon) - Hosts File

Edit: `C:\Windows\System32\drivers\etc\hosts`

```txt
127.0.0.1 mintreu.test
127.0.0.1 api.mintreu.test
127.0.0.1 shopcore-api.mintreu.test
127.0.0.1 helpdesk-api.mintreu.test
127.0.0.1 licenseops-api.mintreu.test
127.0.0.1 shopcore.mintreu.test
127.0.0.1 helpdesk.mintreu.test
```

## 3) Laragon Apache Vhosts (Copy-Paste)

File:
`C:\laragon\etc\apache2\sites-enabled\mintreu-multi.conf`

```apache
<VirtualHost *:80>
    ServerName api.mintreu.test
    DocumentRoot "C:/laragon/www/mintreu/server/official/apiserver/public"
    <Directory "C:/laragon/www/mintreu/server/official/apiserver/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName shopcore-api.mintreu.test
    DocumentRoot "C:/laragon/www/mintreu/server/shopcore/apiserver/public"
    <Directory "C:/laragon/www/mintreu/server/shopcore/apiserver/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName helpdesk-api.mintreu.test
    DocumentRoot "C:/laragon/www/mintreu/server/helpdesk/apiserver/public"
    <Directory "C:/laragon/www/mintreu/server/helpdesk/apiserver/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName licenseops-api.mintreu.test
    DocumentRoot "C:/laragon/www/mintreu/server/licenseops/apiserver/public"
    <Directory "C:/laragon/www/mintreu/server/licenseops/apiserver/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Nuxt local dev reverse proxy examples
<VirtualHost *:80>
    ServerName mintreu.test
    ProxyPreserveHost On
    ProxyPass / http://127.0.0.1:3000/
    ProxyPassReverse / http://127.0.0.1:3000/
</VirtualHost>

<VirtualHost *:80>
    ServerName shopcore.mintreu.test
    ProxyPreserveHost On
    ProxyPass / http://127.0.0.1:3100/
    ProxyPassReverse / http://127.0.0.1:3100/
</VirtualHost>

<VirtualHost *:80>
    ServerName helpdesk.mintreu.test
    ProxyPreserveHost On
    ProxyPass / http://127.0.0.1:3200/
    ProxyPassReverse / http://127.0.0.1:3200/
</VirtualHost>
```

Enable required Apache modules if missing:
`proxy`, `proxy_http`, `rewrite`, `headers`.

## 4) Local Run Matrix

Use separate terminals:

```powershell
# Official API
cd C:\laragon\www\mintreu\server\official\apiserver
php artisan serve --host=127.0.0.1 --port=8000

# Official Nuxt
cd C:\laragon\www\mintreu\server\official\client
npm run dev -- --port 3000

# Shopcore API
cd C:\laragon\www\mintreu\server\shopcore\apiserver
php artisan serve --host=127.0.0.1 --port=8100

# Shopcore Nuxt
cd C:\laragon\www\mintreu\server\shopcore\client
npm run dev -- --port 3100
```

## 5) Hostinger Production Mapping

In hPanel -> Domains/Subdomains -> Document Root mapping:

1. `mintreu.com` -> `public_html/mintreu.com/current/client-dist`
2. `api.mintreu.com` -> `public_html/mintreu.com/current/official-apiserver/public`
3. `shopcore-api.mintreu.com` -> `public_html/shopcore/current/apiserver/public`
4. `helpdesk-api.mintreu.com` -> `public_html/helpdesk/current/apiserver/public`

For each Laravel API subdomain, keep Laravel root one level up from `public`.

Example:

```text
public_html/shopcore/current/apiserver
├── app
├── bootstrap
├── config
├── public   <- subdomain document root
└── vendor
```

## 6) Laravel `.htaccess` (Public Folder)

Put in each API project `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Forward Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 7) Seeder Pipeline (Per API Project)

```powershell
php artisan migrate --force
php artisan db:seed --class=IntegrationSeeder --no-interaction
php artisan db:seed --class=ProductionProjectSeeder --no-interaction
php artisan db:seed --class=ProductionCaseStudySeeder --no-interaction
php artisan db:seed --class=ProductionProductSeeder --no-interaction
```

## 8) SaaS Bridge Env Quick Reference

Official:
1. `MINTREU_SAAS_INTERNAL_KEY`
2. `MINTREU_SAAS_INTERNAL_SECRET`
3. `MINTREU_SAAS_<PROJECT>_LICENSE_KEY`
4. `MINTREU_SAAS_<PROJECT>_LICENSE_SECRET`

Child:
1. `MINTREU_PROJECT_KEY`
2. `SAAS_PRODUCT_SLUG`
3. `MINTREU_SITE_LICENSE_KEY`
4. `MINTREU_SITE_LICENSE_SECRET`
5. `MINTREU_OFFICIAL_BASE_URL`
