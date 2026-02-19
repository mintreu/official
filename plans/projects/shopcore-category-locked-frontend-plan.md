# Shopcore Category-Locked Frontend Plan

Date: 2026-02-19

---

## 1) Objective

Use same backend API for all clients, but sell multiple Nuxt frontend variants by niche.

Example variants:
1. `Velora Boutique Storefront Pack` -> `client_boutique` (dress/boutique/fashion)
2. `PlayNest Toy Storefront Pack` -> `client_toy` (toy/kids)
3. `HavenHaus Furniture Storefront Pack` -> `client_furniture` (furniture/home decor)
4. `LunaMuse Women Storefront Pack` -> `client_women` (women-focused items)

---

## 2) Commercial Rule

1. Sell hosted API subscription.
2. Sell Nuxt frontend ZIP by variant.
3. Do not distribute backend source code.

---

## 3) Category Lock Design

Frontend runtime config keys:
1. `CLIENT_VARIANT`
2. `CATEGORY_LOCK_ENABLED`
3. `ALLOWED_CATEGORIES[]`

Behavior:
1. Category menus only render allowed categories.
2. Product listing page filters out non-allowed categories.
3. Product detail route auto-redirects if category not allowed.
4. Search excludes non-allowed categories.

---

## 4) Packaging Model

Per variant ZIP:
1. `dist/`
2. `site.public.json` with variant + category lock config
3. `SETUP.md`
4. `CHANGELOG.md`

Optional enterprise add-on:
1. Custom category allowlist override
2. Custom hero/branding copy

---

## 5) Delivery Flow

1. Buyer purchases variant pack.
2. Buyer receives variant ZIP and setup guide.
3. Buyer connects frontend to hosted Shopcore API.
4. License validates domain and unlocks runtime config.
