# Chapter 2: Platform Architecture

## 2.1. Technology Stack

The Mintreu Marketplace will leverage a robust and modern technology stack to ensure scalability, maintainability, and a rich user experience.

*   **Backend**: Laravel 12 (PHP 8.2+), Filament (for Admin Panel), Laravel Sanctum (API Authentication), Livewire (for dynamic components), Laravel Boost (development tools).
*   **Frontend**: Nuxt.js 3 (Vue 3), Tailwind CSS (for styling).
*   **Database**: MySQL 8+ (or PostgreSQL), chosen for its reliability and performance with relational data.
*   **Caching/Queue**: Redis, for API rate limiting, session management, and background job processing.
*   **File Storage**: AWS S3 (or compatible S3 storage) for product files, ensuring scalability and reliability.
*   **Payment Gateways**: Stripe, PayPal (for global payment processing).

## 2.2. Backend Architecture (Laravel)

The backend will primarily follow a modular monolithic architecture. This approach allows for rapid development and easier deployment initially, while maintaining clear separation of concerns through well-defined modules (e.g., `Product`, `License`, `Order`, `Admin`, `Developer`, `User`, `Payment`).

*   **Core Application**: Standard Laravel application structure.
*   **Admin Panel**: Built with FilamentPHP, providing a powerful and customizable interface for **Admins** to manage the overall platform.
*   **Developer Panel**: A separate FilamentPHP panel tailored for **Developers** to manage their products, sales, and customers.
*   **API Layer**: A comprehensive RESTful API will serve the Nuxt.js frontend (for customers) and potentially external integrations. Laravel Sanctum will be used for API authentication.
*   **Domain-Driven Design (DDD) Principles**: Where appropriate, DDD principles will be applied to model complex business logic, especially around `Products`, `Licenses`, and `Orders`.

*   **Queues**: Laravel Queues will be extensively used for background processing tasks such as license generation, email notifications, report generation, and payment processing to ensure responsiveness and scalability.
*   **Events**: Laravel Events will be used to decouple application components and react to changes (e.g., `LicenseCreated`, `ProductPurchased`).

## 2.3. Frontend Architecture (Nuxt.js)

The frontend will be a Single Page Application (SPA) built with Nuxt.js, exclusively serving **Customers** and consuming data from the Laravel API.

*   **Nuxt 3**: Leveraging the latest features of Nuxt for server-side rendering (SSR) or static site generation (SSG) where beneficial for SEO and performance, while maintaining a SPA feel.
*   **Vue 3 Composition API**: For building reactive and modular UI components.
*   **Tailwind CSS**: For utility-first styling, ensuring a consistent and customizable design system.
*   **API Communication**: Axios or a similar HTTP client will be used to interact with the Laravel API.
*   **State Management**: Pinia (the official Vuex successor) will be used for centralized state management.
*   **Authentication**: Integration with Laravel Sanctum for secure user authentication and session management.

## 2.4. Database Schema

The database schema will be designed to support the core functionalities of a marketplace, including admins, developers, customers, products, licenses, orders, and usage tracking.

*(Refer to existing `api-usage-tracking-plan.md` and `licensing-system-plan.md` for detailed table structures. This section will provide a high-level overview and cross-reference those detailed plans.)*

**Core Tables (High-Level):**

*   `admins`: Stores Super Admin user information.
*   `developers`: Stores developer user information.
*   `users`: Stores customer user information.
*   `products`: Main product catalog, including type (API, Package, SaaS, etc.), `developer_id`, pricing models.
*   `product_downloads`: File paths, versions for digital products.
*   `licenses`: Key, `product_id`, `user_id` (customer), `developer_id`, expires_at, status, usage_limits.
*   `orders`: `user_id` (customer), `product_id`, amount, status, payment_method.
*   `subscriptions`: `order_id`, billing_cycle, next_billing, status.
*   `api_usages`: `license_id`, endpoint, request_count, period.
*   `usage_summaries`: Aggregated API usage data for reporting and billing.
*   `commissions`: `affiliate_id`, `order_id`, amount, status.
*   `categories`: Name, slug, parent_id for product organization.
*   `reviews`: Customer reviews and ratings for products.
*   `payouts`: Records payments made to developers.

*(Detailed schema for `licenses`, `api_usages`, and `usage_summaries` are already defined in `licensing-system-plan.md` and `api-usage-tracking-plan.md` respectively. These will be directly incorporated or referenced.)*
