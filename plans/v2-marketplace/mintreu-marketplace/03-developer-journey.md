# Chapter 3: The Developer's Journey

This chapter outlines the experience of a developer using the Mintreu Marketplace to sell their products.

## 3.1. Onboarding: Becoming a Mintreu Developer.

Developers will go through a streamlined onboarding process to join the marketplace.

*   **Registration**: Standard user registration (as a `User` model, i.e., a potential customer).
*   **Developer Application**: A dedicated section in the customer dashboard where a `User` can apply to become a `Developer`. This might involve providing:
    *   Company/Personal details.
    *   Payment information for payouts.
    *   Agreement to marketplace terms and conditions.
*   **Approval Process**: Admin review and approval of developer applications. This ensures quality control and adherence to platform standards.
*   **Developer Model Creation**: Upon approval, a new `Developer` model instance will be created, linked to the original `User` account. This `Developer` model will have its own dedicated Filament-based panel.

## 3.2. Product Creation: Listing a new product.

Developers will have a comprehensive Filament-based interface to create and manage their product listings.

*   **Product Information**:
    *   **Name & Slug**: Unique identifier and user-friendly URL.
    *   **Description**: Rich text editor for detailed product descriptions.
    *   **Category/Tags**: For discoverability.
    *   **Media**: Images, videos, and other promotional materials.
    *   **Documentation**: Link to external documentation or upload PDF/Markdown.
    *   **Support Information**: Support channels, response times, etc.
*   **Product Type Selection**: Developers will specify the type of product they are selling (e.g., Laravel Package, API Service, SaaS, Theme, Consultation). This selection will dynamically adjust available fields and options.
*   **File Uploads (for Digital Downloads)**: Secure storage for product files (e.g., ZIP archives of Laravel packages). Integration with AWS S3 or similar for scalable and reliable storage. Version control for product files will be supported.
*   **API Product Configuration**: For API-based products, developers will define:
    *   API endpoints.
    *   Default rate limits.
    *   Usage metrics to track.
    *   Integration instructions for customers.
*   **SaaS Product Configuration**: For SaaS products, developers will define:
    *   Subscription plans.
    *   Feature sets per plan.
    *   Integration with their external SaaS platform (e.g., webhooks for new subscriptions).
*   **Pricing Models**:
    *   **One-time purchase**: For digital downloads.
    *   **Subscription**: Monthly/Annual for SaaS or premium packages.
    *   **Usage-based**: For API products (e.g., per 1000 calls).
*   **Approval for Listing**: New product listings might require Admin approval to maintain marketplace quality.

## 3.3. Package Definition: Creating sales packages.

For each product, developers can define multiple "packages" or tiers to cater to different customer needs and price points.

*   **Package Name & Description**: Clearly differentiate between packages (e.g., "Basic", "Pro", "Enterprise").
*   **Features Included**: List specific features or benefits of each package.
*   **Pricing**: Define the price for each package, adhering to the chosen pricing model (one-time, subscription, usage-based).
*   **License Type Association**: Each package will be linked to a specific license type (e.g., "Basic" package grants a "Regular License", "Enterprise" grants an "Extended License").
*   **Usage Limits (for API/SaaS)**: Define specific usage limits for each package (e.g., "Pro" API package allows 10,000 calls/month, "Basic" allows 1,000 calls/month).
*   **Trial Periods**: Option to offer free trial periods for subscription-based packages.

## 3.4. The Developer Dashboard: Managing products, sales, and customers.

A dedicated Filament-based dashboard will provide developers with a comprehensive overview and management tools.

*   **Product Management**:
    *   View, edit, and delete existing products.
    *   Manage product files and versions.
    *   Manage packages and pricing.
    *   Monitor product status (active, pending review, rejected).
*   **Sales & Order Management**:
    *   View all sales and order history for their products.
    *   Track revenue and earnings.
    *   Generate sales reports.
*   **License Management**:
    *   View all active, expired, and suspended licenses for their products.
    *   Manually issue, suspend, or revoke licenses (with justification).
    *   Generate replacement license keys.
    *   View license usage statistics (for API/SaaS products).
*   **Customer Management**:
    *   View customers (`User` models) who have purchased their products.
    *   Communicate with customers (e.g., through a messaging system or support tickets).
    *   Access customer usage data.
*   **Support & Chat**:
    *   [**Detailed Help and Support System & Chat Service**](./03-6-support-system.md)
*   **Analytics & Reporting**:
    *   Dashboard with key metrics: total sales, top-selling products, customer growth, API usage trends.
    *   Detailed reports on product performance, revenue, and customer demographics.
    *   [**Detailed Product Analytics and Google Analytics Integration**](./03-5-product-analytics.md)
*   **Payouts**:
    *   View payout history.
    *   Manage payout settings (e.g., bank details).
    *   Request manual payouts (if applicable).
*   **Settings**:
    *   Manage developer profile.
    *   Update payment information.
    *   Configure notifications.
