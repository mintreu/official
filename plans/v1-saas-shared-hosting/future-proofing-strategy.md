# Future-Proofing Strategy: From Monolith to Microservices

This document outlines the strategy for building the Modular Monolith in a way that it can be gracefully migrated to the VPS/Microservices architecture (`v1-saas-vps`) in the future with minimal code changes.

## Chapter 1: The Goal - An Evolvable Architecture

Our primary plan is the **Modular Monolith** for shared hosting. However, we will build it with the explicit goal of allowing each "Module" to be extracted into its own "Microservice" later.

The switch from using a local module to a remote microservice will be controlled by a simple configuration flag, requiring no changes to the application's core business logic.

## Chapter 2: The Core Strategy - Interfaces and Drivers

The key to this entire strategy is **programming to an interface, not an implementation**. For each module (e.g., Blog, E-commerce), we will define a contract (a PHP `interface`) that dictates what it can do.

We will then create two implementations (drivers) for that interface:

1.  **`Local` Driver**: This class implements the interface by calling the module's code directly within the monolith. This is the default for the shared hosting environment.
2.  **`Remote` Driver**: This class implements the same interface, but its methods make API calls to an external microservice. This driver will be used when we upgrade to the VPS architecture.

### Example File Structure for a "Blog" Module:

```
/packages/Blog/src/
├── Contracts/
│   └── BlogServiceInterface.php  // The Interface (the contract)
│
├── Drivers/
│   ├── LocalBlogDriver.php       // Implementation 1: For the monolith
│   └── RemoteBlogDriver.php      // Implementation 2: For the microservice future
│
├── Http/Controllers/
│   └── PostController.php        // The local implementation's logic
│
└── Models/
    └── Post.php
```

**`BlogServiceInterface.php` Example:**
```php
<?php
namespace Mintreu\Blog\Contracts;

interface BlogServiceInterface
{
    public function getPosts(int $tenantId): array;
    public function createPost(int $tenantId, array $data): object;
}
```

## Chapter 3: Configuration Management

A new configuration file, `config/modules.php`, will control which driver is used for each module. This allows us to switch between `local` and `remote` mode on a per-module basis.

**`config/modules.php`:**
```php
<?php
return [
    'services' => [
        'blog' => [
            'driver' => env('BLOG_SERVICE_DRIVER', 'local'), // 'local' or 'remote'
            'remote_url' => env('BLOG_SERVICE_URL'),
        ],
        'ecommerce' => [
            'driver' => env('ECOMMERCE_SERVICE_DRIVER', 'local'),
            'remote_url' => env('ECOMMERCE_SERVICE_URL'),
        ],
        // ... other modules
    ],
];
```
By default, everything runs in `local` mode. To migrate the blog module, you would simply update your `.env` file.

## Chapter 4: The Service Provider's Role

A service provider for each module is responsible for reading the configuration and telling Laravel's service container which implementation to use.

**`BlogServiceProvider.php`:**
```php
<?php
namespace Mintreu\Blog;

use Illuminate\Support\ServiceProvider;
use Mintreu\Blog\Contracts\BlogServiceInterface;
use Mintreu\Blog\Drivers\LocalBlogDriver;
use Mintreu\Blog\Drivers\RemoteBlogDriver;

class BlogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BlogServiceInterface::class, function ($app) {
            $config = config('modules.services.blog');

            if ($config['driver'] === 'remote') {
                // If config is 'remote', give the app the Remote Driver
                return new RemoteBlogDriver($config['remote_url']);
            }

            // Otherwise, default to the Local Driver
            return new LocalBlogDriver();
        });
    }
}
```

## Chapter 5: The Migration Path (How to Upgrade to VPS)

When you are ready to upgrade and move your application to a VPS, the process for migrating a module (like the Blog) becomes simple and safe.

1.  **Extract Module to a New Project**: Copy the entire `/packages/Blog` directory into a fresh, separate Laravel project. This new project is your microservice. It already has all the models and controllers it needs.
2.  **Prepare the Microservice**: Set up the new microservice project according to the `service-integration-plan.md`. It will have its own database and be ready to accept API calls.
3.  **Deploy the Microservice**: Deploy the new Blog microservice to your VPS so it's accessible at a specific URL (e.g., `http://blog-service.internal`).
4.  **Update Configuration**: In your main application's (the monolith) `.env` file, make two changes:
    ```env
    # Change the driver for the blog module
    BLOG_SERVICE_DRIVER=remote

    # Add the URL for the new microservice
    BLOG_SERVICE_URL=http://blog-service.internal
    ```
5.  **Done**. You have successfully "strangled" a piece of the monolith. Now, when your main application needs to get blog posts, the `BlogServiceProvider` will provide the `RemoteBlogDriver`, which will make an API call to your new microservice. The rest of your application code remains **completely unchanged**.

This strategy provides a clear, low-risk path to evolve your architecture from a simple, shared-hosting-compatible monolith into a powerful, scalable microservices platform as your business grows.
