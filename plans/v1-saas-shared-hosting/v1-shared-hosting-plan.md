# Mintreu Platform (V1) - Shared Hosting Architecture Plan

## Chapter 1: Acknowledging Constraints & The Monolithic Approach

This plan is specifically designed to work within the limitations of a standard shared hosting environment (like Hostinger). It acknowledges the following critical constraints:

*   **No Programmatic Database Creation**: We cannot create a new database for each user automatically.
*   **Single Application & Database**: All functionality must be served from a single Laravel application connected to a single database.
*   **Limited Process Control**: We cannot run independent background services or complex gateways.

Given these constraints, the only viable architecture is a **Monolith**. However, we will structure it as a **Modular Monolith** to ensure it is clean, scalable, and maintainable.

## Chapter 2: Core Architecture - The Modular Monolith

### 2.1. Multi-Tenancy: Single Database with Automatic Scoping

To solve the multi-tenancy problem in a single database, we will use a dedicated Laravel package like `spatie/laravel-multitenancy`. This is the cornerstone of the shared hosting architecture.

*   **How it Works**:
    1.  We will have a central `tenants` table. Each user or company who subscribes becomes a `Tenant`.
    2.  Every database table that holds tenant-specific data (like `blog_posts`, `ecommerce_orders`) will have a `tenant_id` column.
    3.  The `spatie/laravel-multitenancy` package will automatically and silently add a `WHERE tenant_id = ?` clause to every single Eloquent query.
*   **Benefit**: This completely solves the data separation problem without manual effort. When a tenant is logged in, `Post::all()` will *only* return posts for that tenant. This preserves the clean "Laravel vibe" and avoids cluttering your code with `where` clauses.

### 2.2. Products as Internal Modules (Local Packages)

To avoid a messy, unorganized codebase, each "Product" or "App" (like a Blog, a Project Manager, an E-commerce API) will be developed as a self-contained **local Laravel package** within the main application.

*   **Directory Structure**:
    ```
    /packages/
    ├── Blog/
    │   ├── src/
    │   │   ├── Http/Controllers/
    │   │   ├── Models/ (Post, Comment models)
    │   │   └── ...
    │   ├── database/migrations/
    │   └── routes/api.php
    ├── ECommerce/
    │   ├── src/
    │   └── ...
    └── composer.json
    ```
*   **Modularity**: Each module is self-contained. It has its own routes, controllers, and models. This makes the application easy to understand and maintain. You can enable or disable modules for different tenants based on their subscriptions.
*   **Database Tables**: Each module's migrations will create tables in the single, shared database. To prevent name collisions and for clarity, we **must use table prefixes** (e.g., `blog_posts`, `ecom_products`). While not ideal, this is a necessary and standard practice for a modular monolithic architecture.

### 2.3. Dashboard Strategy: Filament for Simplicity and Speed

While Nuxt.js is powerful, building a full data-management dashboard with it is complex. For a shared hosting environment where simplicity and efficiency are key, using **Filament** for the Tenant Dashboard is the superior choice.

*   **Unified Dashboard**: A single Filament instance can serve different panels. We can have a panel for Super Admins and a separate, customized panel for Tenants.
*   **Rapid Development**: Tenants need to manage their data (blog posts, products, etc.). Filament excels at creating these data-management interfaces (tables, forms) with minimal effort.
*   **How it Works**: When a tenant logs in, the `spatie/laravel-multitenancy` package identifies them. When they visit their Filament dashboard, all the forms and tables they see will automatically be scoped to *their data only*.

## Chapter 3: Handling Multi-Language Projects & Portfolio

This architecture can elegantly handle your goal of showcasing and selling products built in different languages (Python, Node.js, etc.).

1.  **Showcase (Portfolio)**: The main Laravel application will have public-facing pages (standard Blade views) that act as your portfolio. Here you can describe your Python, Node.js, and other projects, show screenshots, and link to demos.
2.  **Sell & License**: The Laravel application will handle the e-commerce and licensing for these external projects. A customer can buy a "Python Script License", and your app will process the payment and issue a license key from the same licensing system used for your PHP modules.
3.  **Distribution**: The "product" the customer receives is a `.zip` file download of the Python/Node.js project.
4.  **Execution (The Limitation)**: We must be clear about the limitation of shared hosting. Your platform **cannot run or host** applications written in other languages. The subscription/sale is for a license to **download and self-host** the code.
5.  **License Validation**: The downloaded Python/Node.js project would need to include a small script that makes an API call back to your main Laravel application's validation endpoint (`/api/license/validate`) to check if the license key is valid.

## Chapter 4: Revised Tenant & Subscription Flow

1.  **Subscription**: A user subscribes to the "Pro Blogging" plan.
2.  **Activation**: The Control Plane flags in the database that this tenant now has access to the `Blog` module.
3.  **Dashboard Access**: When the tenant logs into their Filament dashboard, they now see a new navigation item: "Blog".
4.  **Data Management**: When they click "Blog", they see a Filament resource table listing their blog posts. Because of the multi-tenancy package, they see an empty table. If they create a new post, it's saved to the `blog_posts` table with their `tenant_id`.
5.  **API Access**: If the module includes an API, the tenant can generate an API key from their dashboard. When they call the API, a middleware identifies their tenant based on the API key, and all operations are automatically scoped to their data.

This revised plan is a robust and professional solution tailored specifically for the realities of a shared hosting environment. It provides a clear path to achieving your goals in a maintainable and scalable way.
