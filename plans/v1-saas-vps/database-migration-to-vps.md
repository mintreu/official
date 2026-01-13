# Database Schema Evolution: From Monolith to Microservices

This document outlines how the database schema defined in the `database-schema-plan.md` (for the shared hosting monolith) evolves when migrating to the VPS/Microservices architecture.

## Guiding Principle: Splitting the Database

The core change in this evolution is the move from a **single, shared database** to **multiple databases**:
1.  **One Platform Database**: For the Control Plane.
2.  **Multiple Application Databases**: A dedicated, separate database for each tenant's application instance.

This provides maximum data isolation and scalability.

---

## 1. Control Plane Database Schema

The Control Plane (the main Laravel application) will retain all the **Platform Core** and **Application & API** models. Their schema remains largely the same, with one crucial addition to the `applications` table.

### Unchanged Tables:
- `users`
- `tenants`
- `products`
- `plans`
- `subscriptions`
- `api_keys`

### Modified Tables:

#### `applications`
We add columns to this table to store the connection details for the tenant's dedicated application database.

- `id` (PK)
- `tenant_id` (FK to `tenants.id`)
- `product_id` (FK to `products.id`)
- `name` (string)
- **`db_host`** (string) - *New column*
- **`db_port`** (string) - *New column*
- **`db_name`** (string) - *New column*
- **`db_username`** (string) - *New column*
- **`db_password`** (string, encrypted) - *New column*
- `timestamps`

When the API Gateway authenticates an `ApiKey`, it can now look up the `Application` and immediately find the connection details for the correct downstream database.

---

## 2. Microservice Database Schema (Example: Blog Service)

When a module like the "Blog" is extracted into its own microservice, it gets its own database schema. This schema is simpler than its monolithic counterpart.

### `blog_posts` (in the dedicated Tenant DB)
- `id` (PK)
- `author_id` (FK to `users.id` - Note: User data might be synced or fetched from the Control Plane API)
- `title` (string)
- `slug` (string)
- `content` (longtext)
- `published_at` (timestamp, nullable)
- `timestamps`

**What's Missing?**
The `tenant_id` column is **removed**. It is no longer needed because the *entire database* belongs to a single tenant. The data is isolated at the database level, not the row level. This simplifies all queries within the microservice, making the code cleaner.

### `blog_comments` (in the dedicated Tenant DB)
- `id` (PK)
- `post_id` (FK to `blog_posts.id`)
- `author_id` (FK to `users.id`)
- `content` (text)
- `timestamps`

Again, the `tenant_id` is removed.

---

## 3. The Migration Process

Migrating a tenant from the monolith to the microservice model for a specific product (e.g., the Blog) involves these steps, which can be automated by a CLI tool.

1.  **Provision New Database**: On the VPS, create a new, empty MySQL database for the tenant's blog application (e.g., `tenant_123_blog`).
2.  **Run Microservice Migrations**: The Blog microservice's own database migrations (`create_blog_posts_table`, etc.) are run against this new database.
3.  **Data Transfer**:
    - An automated script connects to the monolith's database.
    - It selects all records from `blog_posts` and `blog_comments` `WHERE tenant_id = 123`.
    - It then inserts this data into the corresponding tables in the new `tenant_123_blog` database (omitting the `tenant_id` column).
4.  **Update Control Plane**: The Control Plane's `applications` table is updated for this tenant's blog application with the new database connection details (`db_host`, `db_name`, etc.).
5.  **Switch to Remote Driver**: The tenant's configuration is updated to use the `remote` driver for the blog service. The next API call will now be routed to the new microservice.
6.  **Decommission Monolith Tables (Optional)**: After all tenants have been migrated off a specific module, the original tables (`blog_posts`, `blog_comments`) can be safely dropped from the monolith's database, completing the "strangling" of that piece of functionality.
