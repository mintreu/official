# Chapter 1: Introduction

## 1.1. Vision: The all-in-one platform for Laravel developers.

Mintreu aims to become the premier marketplace for Laravel developers to showcase, sell, and manage their digital products. This platform will empower developers to monetize their creations, ranging from API services and packages to full-fledged applications, while providing customers with a trusted source for high-quality Laravel-centric solutions. Our vision is to foster a vibrant ecosystem where innovation thrives and developers can easily connect with their target audience.

## 1.2. Target Audience: Who is this for?

*   **Product Developers (Sellers)**: Laravel developers, agencies, and teams who create reusable code, APIs, packages, themes, or services and wish to sell them to a broader audience. They seek a platform that handles licensing, payments, and customer management, allowing them to focus on development.
*   **Laravel Developers (Buyers)**: Individuals and businesses building Laravel applications who are looking for reliable, well-supported, and efficient solutions to integrate into their projects. They value quality, clear licensing, and good documentation.
*   **Affiliates**: Individuals or entities who wish to promote products listed on Mintreu and earn commissions on successful sales.

## 1.3. Core Concepts: Products, Developers, Customers, Licenses, and Admins.

*   **Products**: Any digital asset or service offered for sale on the platform. This includes, but is not limited to, Laravel packages, API services, SaaS applications, themes, and consultation services. Products can have different "packages" or tiers (e.g., basic, premium, enterprise).
*   **Developers**: A dedicated `Developer` model will represent users who register on the platform with the intent to sell their products. They have access to a dedicated Filament-based dashboard to manage their listings, sales, customers, and analytics.
*   **Customers**: The existing `User` model will now primarily represent customers who purchase products from the marketplace. They have a frontend dashboard (built with Nuxt.js) to manage their purchased licenses, download products, access support, and track usage for API-based products.
*   **Admins**: A dedicated `Admin` model will represent Super Admins who manage the overall platform through a separate Filament admin panel.
*   **Licenses**: Digital entitlements that grant customers the right to use a product under specific terms. Licenses are central to managing access, enforcing usage limits, preventing piracy, and handling renewals/expirations. Each product sale will typically generate one or more licenses.