# Mintreu Platform (V1) - Service Integration Plan

This document provides a detailed blueprint for integrating any external Laravel application as a "Service" into the Mintreu Control Plane, making it available as a subscribable "Product" for tenants.

## Chapter 1: The Service Philosophy

A "Service" is a standalone, independently deployable Laravel application that provides the business logic for a Product. It adheres to the following principles:

1.  **Headless and API-Only**: A Service has no public-facing UI. Its sole purpose is to provide a RESTful or GraphQL API. All `routes/web.php` should be removed or disabled.
2.  **Stateless**: The Service should not rely on the session. Every API request must be authenticated and authorized by the API Gateway.
3.  **Billing-Agnostic**: The Service does **not** know about subscriptions, pricing, or billing. It only cares about executing its task. It's the Control Plane's job to decide if a tenant is *allowed* to use the Service.
4.  **Tenant-Aware**: The Service must be able to handle requests for multiple tenants and keep their data separate. It expects the Control Plane to tell it *which* tenant's data to operate on for any given request.

## Chapter 2: Preparing a Laravel Project to be a Service

To make an existing or new Laravel project compatible with the Mintreu platform, follow these steps.

### Step 2.1: Dynamic Database Connection

The Service must not have a hardcoded database connection. It must be able to connect to a different database for each request, as determined by the Control Plane.

**Action:** Modify `config/database.php`. Add a new connection configuration that reads its values from the request headers or a similar dynamic source. A middleware will handle setting this up.

```php
// In config/database.php

'connections' => [
    // ... other connections like mysql, sqlite

    'tenant' => [
        'driver'         => 'mysql',
        'host'           => env('DB_HOST', '127.0.0.1'),
        'port'           => env('DB_PORT', '3306'),
        'database'       => null, // This will be set dynamically
        'username'       => env('DB_USERNAME', 'forge'),
        'password'       => env('DB_PASSWORD', ''),
        'charset'        => 'utf8mb4',
        'collation'      => 'utf8mb4_unicode_ci',
        'prefix'         => '',
        'strict'         => true,
        'engine'         => null,
    ],
],
```

### Step 2.2: Create a Tenant Resolution Middleware

This middleware is the heart of the Service's multi-tenancy. It will run on every incoming request from the API Gateway. Its job is to read the tenant identification headers and configure the application to use the correct database.

**Action:** Create a new middleware, e.g., `app/Http/Middleware/ResolveTenant.php`.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        // The Gateway will add the tenant's database name to this header
        $dbName = $request->header('X-Tenant-DB-Name');

        if (! $dbName) {
            // If no tenant DB is identified, abort.
            abort(403, 'Tenant could not be identified.');
        }

        // Set the database connection config for the 'tenant' connection
        Config::set('database.connections.tenant.database', $dbName);

        // Set the default connection to our dynamic tenant connection
        Config::set('database.default', 'tenant');

        // Purge the old connection and reconnect to ensure the new settings are used
        DB::purge('tenant');
        DB::reconnect('tenant');

        return $next($request);
    }
}
```

**Action:** Register this middleware in `app/Http/Kernel.php` for all API routes.

```php
// In app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        \App\Http\Middleware\ResolveTenant::class, // Add this line
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

### Step 2.3: Isolate Migrations

The Service must contain all the database migrations required for its own functionality (e.g., a `blog` service would have `create_posts_table` and `create_comments_table` migrations). These migrations will be run by the Control Plane when a new `Application` is provisioned for a tenant.

## Chapter 3: The Service Onboarding Process (Control Plane)

Once a Service is prepared, a Super Admin can onboard it via the Filament-based Platform Admin Panel.

### Step 3.1: Deploy the Service

The prepared Laravel project must be deployed to a location accessible by the Control Plane (e.g., via an internal network or VPC).
*Example URL: `http://blog-service.internal`*

### Step 3.2: Register the Service & Its Routes

In the Platform Admin Panel, the Super Admin will:

1.  **Create a `Service` record:**
    *   **Name**: "Blog Service"
    *   **Slug**: `blog`
    *   **Base URI**: `http://blog-service.internal` (The internal URL where the service is running)

2.  **Create `RouteMapping` records for the Service:** This tells the API Gateway how to proxy requests.
    *   **Mapping 1:**
        *   **Service**: `blog`
        *   **Incoming Path**: `/api/products/blog/v1/posts`
        *   **Downstream Path**: `/api/v1/posts`
    *   **Mapping 2:**
        *   **Service**: `blog`
        *   **Incoming Path**: `/api/products/blog/v1/posts/{id}`
        *   **Downstream Path**: `/api/v1/posts/{id}`

### Step 3.3: Create the Public-Facing Product

Finally, the Super Admin creates the `Product` that tenants will see and subscribe to.

1.  **Create a `Product` record:**
    *   **Name**: "Awesome Blog"
    *   **Description**: "A fully-featured blog API for your application."
    *   **Linked Service**: `blog` (The `Service` record created above)
2.  **Define Pricing Tiers**:
    *   **Free Tier**: 1,000 API calls/month
    *   **Pro Tier**: 100,000 API calls/month, $10/month

The "Awesome Blog" product is now live and can be subscribed to by tenants.

## Chapter 4: The Provisioning & Request Lifecycle

This flow ties everything together.

1.  **Provisioning**:
    *   A Tenant subscribes to the "Awesome Blog" product in their Nuxt.js dashboard.
    *   The Control Plane triggers a provisioning job.
    *   The job:
        1.  Creates a new, empty database (e.g., `tenant_123_blog_db`).
        2.  Runs the `blog` Service's migrations against this new database.
        3.  Generates a Client ID/Secret for the Tenant's new `Application`.
        4.  Stores the mapping: `Tenant 123` -> `Awesome Blog App` -> `tenant_123_blog_db`.

2.  **API Request**:
    *   The Tenant's app makes a `GET` request to `https://api.mintreu.com/api/products/blog/v1/posts` with its API keys.
    *   **The API Gateway (Control Plane) does the following:**
        1.  Authenticates the key, identifying Tenant 123 and their "Awesome Blog" `Application`.
        2.  Authorizes the request (checks for an active subscription).
        3.  Looks up the route mapping and sees the request should go to the `blog` service.
        4.  Retrieves the tenant's database name: `tenant_123_blog_db`.
        5.  Proxies a new `GET` request to `http://blog-service.internal/api/v1/posts`, adding the crucial header: `X-Tenant-DB-Name: tenant_123_blog_db`.
    *   **The Blog Service does the following:**
        1.  The `ResolveTenant` middleware runs, reads the header, and configures the database connection to use `tenant_123_blog_db` for this request.
        2.  The `PostController` executes, queries the `posts` table (which is inside `tenant_123_blog_db`), and returns a JSON response.
    *   **The API Gateway** receives the response from the Blog Service and relays it back to the Tenant's app.

This architecture provides maximum isolation and scalability, perfectly aligning with the V1 vision.
