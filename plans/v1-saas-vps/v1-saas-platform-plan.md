# Mintreu Platform (V1) - Multi-Tenant SaaS Plan

## Chapter 1: Vision & Core Concepts

### 1.1. Vision

To create a multi-tenant Software-as-a-Service (SaaS) platform, in the style of Wix or Heroku, where users (Tenants) can subscribe to a variety of first-party digital products and services. Tenants can instantly provision, configure, and manage applications (e.g., an e-commerce backend, a blog, a licensed software instance) through a centralized, user-friendly dashboard.

The platform will serve as a unified **Control Plane** for authentication, subscription management, and billing, while the actual product logic will be handled by modular, independent **Services**, which can include other full Laravel applications.

### 1.2. Core Concepts

*   **Tenant**: A registered user or organization that subscribes to products. Each tenant is isolated from others.
*   **Product**: A service offered by the Mintreu platform. Examples:
    *   **API-Based Service**: E-commerce API, Blog API, Analytics API.
    *   **Licensed Digital Product**: A downloadable script or plugin with a license key.
    *   **Full Application**: A pre-built, configurable application like a complete e-commerce store.
*   **Application (or "App")**: An instance of a Product that a Tenant has provisioned. For example, Tenant A's "My Web Store" is an `Application` that is an instance of the "E-commerce" `Product`. Each `Application` has its own configuration, data, and API credentials.
*   **Control Plane**: The primary Laravel application. It is the brain of the entire system, responsible for:
    *   User/Tenant registration and management.
    *   The product catalog.
    *   Subscriptions and billing.
    *   The Tenant Dashboard API.
    *   Acting as the central **API Gateway**.
*   **Service**: An independent, deployable application (likely another Laravel project) that provides the core logic for a `Product`. For example, a dedicated Laravel project that contains all the business logic for an e-commerce API.

## Chapter 2: High-Level Architecture

This architecture is designed for scalability, separation of concerns, and maintainability.

### 2.1. The Control Plane & API Gateway Model

The system will be architected around a central **Control Plane** (the main Laravel `backend` project) that also functions as an **API Gateway**.

1.  **Control Plane**: Manages all platform-level concerns. It does **not** contain the business logic for the end-user products themselves.
2.  **API Gateway**: All incoming API requests from a Tenant's `Application` first hit the API Gateway. The gateway is responsible for:
    *   **Authentication**: Validating the `Application`'s API Key/Secret.
    *   **Authorization**: Checking if the Tenant's subscription for that `Product` is active.
    *   **Rate Limiting & Usage Tracking**: Enforcing usage quotas.
    *   **Request Proxying**: Forwarding the validated request to the appropriate downstream **Service**.
3.  **Services**: These are separate Laravel applications that contain the actual product logic. They are unaware of billing or subscription details. They simply execute business logic based on the incoming request, which will include tenant identification.

![Architecture Diagram](https://i.imgur.com/9g8G4fW.png)

### 2.2. Dashboard Strategy: Filament vs. Nuxt.js

The best approach is to use both, leveraging their respective strengths.

*   **Platform Admin Panel (Filament)**: A panel within the main Laravel `backend` for **Super Admins**. Used to manage tenants, products, system-wide settings, and view platform analytics. Filament is perfect for this as it's incredibly fast for building internal, data-centric back-office tools.
*   **Tenant Dashboard (Nuxt.js)**: The existing `client` application will be the dashboard for **Tenants**. This provides a rich, bespoke, and highly polished user experience. Here, tenants will:
    *   Manage their profile and billing.
    *   Subscribe to new `Products`.
    *   Provision new `Applications`.
    *   Generate and manage API keys for their `Applications`.
    *   Configure their `Applications` (e.g., set the store name, theme colors).
    *   View usage analytics.

### 2.3. Multi-Tenancy Database Strategy

A **hybrid approach** offers the best balance of isolation and manageability.

*   **Platform Database (Shared)**: A single, main database for the Control Plane. It stores platform-level data: `users`, `tenants`, `products`, `subscriptions`, `invoices`, `api_keys`.
*   **Application Databases (Dedicated)**: For each `Application` a Tenant provisions that requires data storage (like a blog or e-commerce store), a **separate database** will be dynamically created.
    *   **Pros**: Perfect data isolation. Scalability (databases can be moved to different servers). Simplified queries within the `Service` (no need for `where tenant_id`).
    *   **Cons**: Higher management overhead (automating database creation and migrations).
    *   **Implementation**: The Control Plane will manage a "master" connection to create/drop databases. When proxying a request, the API Gateway will dynamically configure the database connection for the downstream `Service` based on the authenticated tenant.

## Chapter 3: The Tenant Journey & Authentication

1.  **Registration & Subscription**: A user registers on the Nuxt.js frontend and becomes a `User`. They can then create a `Tenant` (or be assigned to one). In their dashboard, they browse the `Product` catalog and subscribe to a product (e.g., "E-commerce API - Pro Tier").
2.  **Application Provisioning**: The Tenant creates a new `Application`, giving it a name like "My Awesome Store".
3.  **API Key Generation**: The Control Plane generates a unique **Client ID** and **Client Secret** for this new `Application`. These keys are tied to the Tenant and the specific product subscription.
4.  **API Usage**: The Tenant builds their own frontend (or uses a provided one). When their application needs to call the E-commerce API, it sends a request to `api.mintreu.com/products/ecommerce/v1/orders` with the Client ID/Secret in the `Authorization` header.
5.  **Gateway Processing**: The Mintreu API Gateway receives the request, validates the credentials, checks the subscription status, records the API call for billing, and forwards the request to the E-commerce `Service`.
6.  **Service Execution**: The E-commerce `Service`, now connected to this Tenant's dedicated application database, processes the request (e.g., creates an order) and returns a response, which is passed back through the gateway to the Tenant's application.

## Chapter 4: Onboarding New Products & Services

This model makes it straightforward to add new products.

1.  **Develop the Service**: Create a new, standard Laravel application that contains the product's business logic.
2.  **Register the Service**: In the Control Plane's Admin Panel, a Super Admin registers this new `Service`, defining:
    *   A unique slug (e.g., `ecommerce-api`).
    *   The internal base URL of the service (e.g., `http://ecommerce-service.internal`).
    *   The routes it exposes that should be available through the gateway.
3.  **Create the Product**: Create a new `Product` in the Control Plane, linking it to the newly registered `Service`.
4.  **Define Pricing**: Create pricing tiers (e.g., Free, Pro, Enterprise) for the `Product`.
5.  **Publish**: The product is now available for tenants to subscribe to in the Nuxt.js dashboard.

This approach allows Mintreu to offer a growing portfolio of services, including those from completely separate and isolated Laravel projects, without modifying the core Control Plane. It provides the "virtual hosting" solution by abstracting the underlying service into a subscribable `Product`.
