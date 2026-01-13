# Feature Plan: Downloadable Starter Kits

This document details the strategy and implementation for offering pre-configured, downloadable frontend starter kits to users upon subscribing to an API-based product.

## 1. The Concept

Instead of the platform hosting the user's frontend, we provide them with a complete, ready-to-deploy frontend application in a ZIP file. This application is pre-configured to communicate with their specific, provisioned backend service.

This feature empowers developers by giving them full control over the frontend code and deployment, while the Mintreu platform remains the robust, managed backend.

## 2. The Generation Process

The generation of a starter kit will be an on-demand, automated process handled by a queued job to ensure the UI remains responsive.

**Workflow:**
1.  **User Action:** In the Tenant Dashboard, after provisioning an `Application` (e.g., "My New Blog"), the user clicks a "Download Frontend Starter Kit" button.
2.  **Job Dispatch:** A new `GenerateStarterKit` job is dispatched to the queue.
3.  **Template Cloning:** The job clones a private, generic template repository (e.g., `mintreu/nuxt-blog-template`) into a temporary, secure directory on the server.
4.  **Credential Injection:**
    - The job retrieves the `ApiKey` (key and secret) associated with the user's `Application`.
    - It reads a template file within the cloned repo (e.g., `.env.example`).
    - It populates the template with the user's specific credentials and the correct API endpoint, creating a new `.env` file.
    ```ini
    # .env.example
    NUXT_PUBLIC_API_BASE_URL=
    NUXT_PUBLIC_API_KEY=
    NUXT_PRIVATE_API_SECRET= # Note: This is for server-side use in Nuxt only
    ```
    ```ini
    # Generated .env file
    NUXT_PUBLIC_API_BASE_URL=https://api.mintreu.com/products/blog/v1
    NUXT_PUBLIC_API_KEY=pk_user_123_abc...
    NUXT_PRIVATE_API_SECRET=sk_user_123_xyz...
    ```
5.  **Packaging:**
    - A prominent `README.md` file is added to the package with security warnings and deployment instructions.
    - The entire temporary directory is compressed into a ZIP file (e.g., `my-new-blog-starter-kit-v1.0.zip`).
6.  **Secure Download:**
    - The ZIP file is stored in a private, temporary location (e.g., a private S3 bucket or local storage).
    - A secure, time-limited download link is generated and made available to the user in their dashboard.
    - A cleanup job removes the temporary ZIP file after a set period (e.g., 24 hours).

## 3. User Experience & Responsibilities

We must be explicit about the division of responsibility.

### Our Responsibility:
-   Provide a clean, working, and secure backend API.
-   Ensure the downloaded starter kit is correctly configured to connect to that API.
-   Provide versioning and changelogs for the starter kit templates.

### The User's Responsibility:
-   **Security:** Securely managing their API credentials and **never** committing them to public version control.
-   **Deployment:** Hosting the downloaded application on their own choice of provider (Vercel, Netlify, VPS, etc.).
-   **Customization:** Any and all modifications to the source code.
-   **Updates:** Manually updating their application when new versions of the starter kit are released.

The `README.md` file within the ZIP will clearly state these points.

## 4. Strategic Impact

This feature significantly enhances our platform's appeal to our target developer audience.

-   **Reduces Friction:** It is the fastest possible way for a user to go from subscription to a working application.
-   **Increases Stickiness:** Even though the user hosts the frontend, it's deeply integrated with our backend API, making them a long-term customer.
-   **Clarifies Our Role:** It solidifies our identity as a **Backend-as-a-Service (BaaS)** provider, focusing our efforts on creating world-class APIs without the complexity of frontend hosting.
-   **Creates a "Best of Both Worlds" Scenario:** Users get the convenience of a managed backend and the flexibility of a self-hosted frontend. This is a powerful competitive advantage over both fully-managed systems (like Wix) and fully-unmanaged systems (like AWS).
