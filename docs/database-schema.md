# Database Schema

This document outlines the database schema for the backend application, based on the models required by the frontend.

## User Model

The `users` table stores user account information for authentication.

-   `id`: `bigIncrements` (Primary Key)
-   `name`: `string`
-   `email`: `string`, `unique`
-   `email_verified_at`: `timestamp`, `nullable`
-   `password`: `string`
-   `about`: `text`, `nullable`
-   `remember_token`: `rememberToken`
-   `timestamps`: `timestamps`

## Project Model

The `projects` table stores information about the featured projects.

-   `id`: `bigIncrements` (Primary Key)
-   `title`: `string`
-   `description`: `text`
-   `icon`: `string`
-   `platform`: `string`
-   `tech`: `json`
-   `duration`: `string`
-   `result`: `string`
-   `timestamps`: `timestamps`

## Case Study Model

The `case_studies` table stores information about client success stories.

-   `id`: `bigIncrements` (Primary Key)
-   `title`: `string`
-   `challenge`: `text`
-   `solution`: `text`
-   `results`: `json` (Array of objects with `value` and `label`)
-   `techStack`: `json` (Array of strings)
-   `icon`: `string`
-   `timestamps`: `timestamps`

## Service Model

The `services` table stores information about the services offered.

-   `id`: `bigIncrements` (Primary Key)
-   `title`: `string`
-   `icon`: `string`
-   `description`: `text`
-   `features`: `json` (Array of strings)
-   `timestamps`: `timestamps`

## Category Model

The `categories` table stores information about product categories.

-   `id`: `bigIncrements` (Primary Key)
-   `name`: `string`
-   `slug`: `string`, `unique`
-   `timestamps`: `timestamps`

## Product Model



The `products` table stores information about the products in the marketplace.



-   `id`: `bigIncrements` (Primary Key)

-   `name`: `string`

-   `icon`: `string`

-   `category_id`: `foreignId` (References `id` on `categories` table)

-   `description`: `text`

-   `features`: `json` (Array of strings)

-   `price`: `unsignedBigInteger`

-   `demo_url`: `string`, `nullable`

-   `demo_admin_url`: `string`, `nullable`

-   `timestamps`: `timestamps`



## License Model



The `licenses` table stores information about product licenses purchased by customers.



-   `id`: `bigIncrements` (Primary Key)

-   `user_id`: `foreignId` (References `id` on `users` table)

-   `product_id`: `foreignId` (References `id` on `products` table)

-   `license_key`: `string`, `unique`

-   `expires_at`: `timestamp`, `nullable`

-   `timestamps`: `timestamps`
